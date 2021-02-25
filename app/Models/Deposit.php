<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable= [

        'amount','user_id', 'paystack_reference'
    ];

    public function transactions()
    {
    return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }



}
