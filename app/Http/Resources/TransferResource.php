<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'amount'=> $this->amount,
            'transfer_from'=> $this->transfer_from,
            'transfer_to'=> $this->transfer_to,
            'date'=> $this->created_at->format('d M Y')
        ];
    }

    public function with($request)
    {
        return [
            'status'=>true,
            'message'=>'Transfer completed successfully'
        ];
    }
}
