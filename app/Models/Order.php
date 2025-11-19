<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // app/Models/Order.php
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'lyrics',
        'order_type',
        'artist_id',
       
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

}
