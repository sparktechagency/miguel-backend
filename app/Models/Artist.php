<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable =[
       'name', 'description', 'profile', 'gender','singer','singer_writer','location','is_wishlisted','is_followed'
    ];
}
