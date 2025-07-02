<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

        // app/Models/Transaction.php
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'status',
        'payment_method',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }

}
