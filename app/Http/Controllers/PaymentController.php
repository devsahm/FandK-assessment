<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use App\Models\Wallet;
use Validator;
use App\Mail\DepositWasSuccessful;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\PaymentTrait;

class PaymentController extends Controller
{

    use PaymentTrait;
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function __construct() {

        $this->middleware(['auth:api']);

    }

     public function  depositFunds(Request $request)
     {
        $validator=Validator::make($request->all(), [
            'amount'=> 'required|numeric'
        ]);

        if ( $validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user= auth()->user();
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = time()
                 . mt_rand(1000000, 9999999)
                 . $chars[rand(0, strlen($chars) - 1)]
                 .substr(str_shuffle(MD5(microtime())), 0, 3);
        $email = $user->email;
        $total_amount = $request->amount;
        $callback_url = route('paystackCallback');
        $paymentInitialization = $this->InitiliazePaystackPayment($email, $total_amount, $callback_url, $reference);
         return  $paymentInitialization;

     }




    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function paystackCallbackURL(Request $request)
    {
        $reference = $request->get('reference');
        $paymentDetails = $this->paystackVerifyPayment($reference);
        if ($paymentDetails['status']===true) {
            $amount_paid= $paymentDetails['data']['amount'] / 100;

            try{

             DB::beginTransaction();
            //Create the record in the deposit table for the user
             $deposit = Deposit::create([
                'amount'=> $amount_paid,
                'user_id'=>auth()->id(),
                'paystack_reference'=>$paymentDetails['data']['reference']
             ]);

                //Create the record on the transactions table with the Deposit Model
                $deposit->transactions()->create([
                    'user_id'=>auth()->id(),
                    'type'=>'+',
                    'amount'=>$amount_paid
                ]);

                //Check if the user already have a wallet, if yes update the balance column, if no, create a Wallet record for the user
                $user= auth()->user();
                $wallet= $user->wallet;
                $wallet==null ?
                $balance= $amount_paid :  $balance= $wallet->balance + $amount_paid;
                Wallet::updateOrCreate(
                    ['user_id' => auth()->user()->id],
                    ['balance' =>$balance]
                );
                DB::commit();

                //Send Email to the user and queue it
                mail::to($user->email)->queue(new DepositWasSuccessful($deposit));
                return new DepositResource($deposit);

            }catch(\Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status'=>false,
                    'message'=> 'Ops, something went wrong, your balance will be updated very soon'.$e->getMessage()
                ], 400);

            }

        }else{

            return response()->json([
                'status'=>false,
                'message'=>'Fund deposit was not completed'
            ], 400);
        }


    }
}
