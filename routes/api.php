<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => AuthController::class], function () {
    Route::post('register', 'register')->withoutMiddleware('auth:sanctum');
    Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    Route::post('otp-verify', 'otpVerify')->withoutMiddleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->withoutMiddleware('auth:sanctum');

    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        Route::post('create-new-password', 'createNewPassword');
        Route::put('update-profile', 'updateProfile');
        Route::get('profile', 'profile');
    });
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(BannerController::class)->group(function () {
        Route::get('banner', 'banner')->middleware('user');
        Route::post('create-banner', 'createBanner')->middleware('admin');
    });
    Route::controller(UserController::class)->middleware('admin')->group(function () {
        Route::get('users', 'users');
        Route::patch('update-banned-status/{userId}', 'updateBannedStatus');
    });
    Route::controller(CategoryController::class)->group(function () {
        // Genre
        Route::get('genre', 'genre')->middleware('user');
        Route::post('create-genre', 'createGenre')->middleware('admin');
        Route::delete('delete-genre/{genreId}', 'deleteGenre')->middleware('admin');

        // BNP
        Route::get('bpm', 'bpm')->middleware('user');
        Route::post('create-bpm', 'createBpm')->middleware('admin');
        Route::delete('delete-bpm/{bnpId}', 'deleteBpm')->middleware('admin');

        // Key
        Route::get('key', 'key')->middleware('user');
        Route::post('create-key', 'createKey')->middleware('admin');
        Route::delete('delete-key/{keyId}', 'deleteKey')->middleware('admin');

        // License
        Route::get('license', 'license')->middleware('user');
        Route::post('create-license', 'createLicense')->middleware('admin');
        Route::delete('delete-license/{licenseId}', 'deleteLicense')->middleware('admin');

        // Type
        Route::get('type', 'type')->middleware('user');
        Route::post('create-type', 'createType')->middleware('admin');
        Route::delete('delete-type/{typeId}', 'deleteType')->middleware('admin');
    });
    Route::controller(ArtistController::class)->group(function () {
        Route::get('artist', 'artist')->Middleware('user');
        Route::post('create-artist', 'createArtist')->Middleware('admin');
        Route::put('update-artist', 'updateArtist')->Middleware('admin');
        Route::delete('delete-artist/{artistId}', 'deleteArtist')->Middleware('admin');
    });
});