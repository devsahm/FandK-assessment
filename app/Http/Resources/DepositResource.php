<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'reference' => $this->paystack_reference,
            'deposited_amount' => $this->amount,
            'currency'=>'NGN',
            'date'=>$this->created_at->format('d M Y')
        ];
    }

    public function with($request)
    {
        return[
            'status'=>true,
            'message'=>'Deposit Successful'
        ];
    }
}
