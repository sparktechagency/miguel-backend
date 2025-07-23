<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable =[
        "user_id",
        "artist_id",
        "is_followed"
    ];

    public function artist(){
        return $this->belongsTo(Artist::class);
    }
}
