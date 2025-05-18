<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'ADMIN',
                'is_banned' => false,
                'verify_email' => true,
                'otp_verified_at' => true,
            ]
        );
        User::updateOrCreate(
            [
                'first_name' => 'Regular',
                'last_name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'USER',
                'is_banned' => false,
                'verify_email' => true,
                'otp_verified_at' => true,
            ]
        );
    }
}
