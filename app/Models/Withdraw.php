<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable =[
        'amount'
    ];

    public function transactions()
    {
    return $this->morphMany(Transaction::class, 'transactionable');
    }
}
