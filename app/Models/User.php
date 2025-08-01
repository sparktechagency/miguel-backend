<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [
        'full_name', 'email', 'password', 'contact', 'location', 'avatar', 'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        // 'otp',
        'otp_expires_at',
        'otp_verified_at'
    ];
    protected $casts = [
        'is_banned' => 'boolean',
        'otp_expires_at' => 'datetime',
        'otp_verified_at' => 'datetime',
    ];


}
