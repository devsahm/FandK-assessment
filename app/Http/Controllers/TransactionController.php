<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdraw;
use App\Http\Resources\TransferResource;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }


    public function transferFunds(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'amount'=>'required',
            'username'=> 'required|exists:users,username'
        ]);


        if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
        }

        $receiver_username= $request->username;
        //check if user have enough balance in the wallet to process the transfer
        $user= auth()->user();
        $sender_balance= $user->wallet->balance;
        $amount= $request->amount;
        if ($sender_balance < $request->amount) {
           return response()->json([
               'status'=> 'false',
               'message'=>'insufficient funds'
           ], 400);
         }
         //check if the username is the same as the authenticated username
         if ($request->username == $user->username) {
            return response()->json([
                'status'=> 'false',
                'message'=>'You cannot use your username for this request'
            ], 400);
         }



         try{

            DB::beginTransaction();

            //update the sender's wallet
            $sender_balance -= $amount;
            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $sender_balance]);


            //Add withdrawl record for sender
            $withdraw=Withdraw::create([
               'amount'=>$amount,
            ]);

           //Add a Transaction Record for sender
           $withdraw->transactions()->create([
               'user_id'=>$user->id,
               'type'=> '-',
               'amount'=> $amount,
           ]);


           //Create a new Transfer Record for the receiver, add a Transaction record for the reciever and update the reciever's wallet
            $receiver= User::where('username', $receiver_username)->first();
            $transfer= Transfer::create([
                'amount'=> $amount,
                'transfer_from'=>$user->username,
                'transfer_to'=>$receiver->username,
            ]);

            //add a Transaction record for the reciever
            $transfer->transactions()->create([
                'user_id'=>$receiver->id,
                'type'=> '+',
                'amount'=> $amount,
            ]);

            //update the reciever's wallet
            $receiver_wallet=$receiver->wallet;
            $receiver_wallet==null?
            $receiver_balance= $amount : $receiver_balance =  $receiver_wallet->balance + $amount;
             Wallet::updateOrCreate(
               ['user_id' => $receiver->id],
               ['balance' =>$receiver_balance]
           );

            DB::commit();
            return new TransferResource($transfer);

         }catch(\Throwable $e){

            DB::rollBack();
            return response()->json([
                'status'=>false,
                'message'=> 'Transfer failed'.$e->getMessage()
            ], 400);

         }

    }


}
