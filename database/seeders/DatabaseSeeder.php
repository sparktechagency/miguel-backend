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
                'full_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('Password@123'),
                'role' => 'ADMIN',
                'is_banned' => false,
                'otp_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            [
                'full_name' => 'Regular',
                'email' => 'user@gmail.com',
                'password' => bcrypt('Password@123'),
                'role' => 'USER',
                'is_banned' => false,
                'otp_verified_at' => now(),
            ]
        );
    }
}
