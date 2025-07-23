<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable =[
        "user_id",
        "artist_id",
        "is_wishlisted"
    ];

    public function artist(){
        return $this->belongsTo(Artist::class);
    }
}
