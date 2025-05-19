<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => AuthController::class], function () {
    Route::post('register', 'register')->withoutMiddleware('auth:sanctum');
    Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    Route::post('otp-verify', 'otpVerify')->withoutMiddleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->withoutMiddleware('auth:sanctum');
    // Route::post('forgot-password', 'forgotPassword')->withoutMiddleware('auth:sanctum');

    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        Route::post('create-new-password', 'createNewPassword');
        Route::put('update-profile', 'updateProfile');
        Route::get('profile', 'profile');
    });
});