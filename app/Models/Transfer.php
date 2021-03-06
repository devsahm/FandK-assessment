<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

protected $fillable=[
    'amount', 'transfer_from', 'transfer_to'
];
    public function transactions()
    {
    return $this->morphMany(Transaction::class, 'transactionable');
    }


}
