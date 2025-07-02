<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    // app/Models/OrderDetails.php
    protected $fillable = [
        'order_id',
        'song_id',
        'price',
        'total',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function song(){
        return $this->belongsTo(Song::class);
    }
}
