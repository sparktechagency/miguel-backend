<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
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
Route::controller(ContactController::class)->group(function () {
    Route::post('contact', 'contact');
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('user')->controller(WishlistController::class)->group(function () {
        Route::get('wishlist', 'wishlist');
        Route::post('create-wishlist/{artistId}', 'createWishlist');
        Route::post('remove-wishlist/{artistId}', 'removeWishlist');
    });
    Route::middleware('user')->controller(FollowController::class)->group(function () {
        Route::post('follow/{artistId}', 'createFollow');
        Route::get('follow', 'follow');
        Route::patch('unfollow/{artistId}', 'unfollow');
    });
    Route::middleware('user')->controller(SupportController::class)->group(function () {
        Route::post('support', 'support');
    });
    Route::middleware('user')->controller(PaymentController::class)->group(function () {
        Route::post('create-payment-intent', 'createPaymentIntent');
    });

    /* ---------------------------------- admin ------------------------------*/
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->middleware('admin');
    });
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
        Route::get('artist-detail/{artistId}', 'artistDetail')->Middleware('user');

        Route::post('create-artist', 'createArtist')->Middleware('admin');
        Route::put('update-artist/{artistId}', 'updateArtist')->Middleware('admin');
        Route::delete('delete-artist/{artistId}', 'deleteArtist')->Middleware('admin');
    });
    Route::controller(SongController::class)->group(function () {
        Route::get('song', 'song')->Middleware('user');
        Route::get('publish-song', 'publishSong')->Middleware('user');
        Route::get('latest-trending', 'latestTrending')->Middleware('user');

        Route::post('create-song', 'createSong')->Middleware('admin');
        Route::put('update-song/{songId}', 'updateSong')->Middleware('admin');
        Route::patch('published/{songId}', 'published')->Middleware('admin');
        Route::delete('delete-song/{songId}', 'deleteSong')->Middleware('admin');
    });
    Route::controller(TransactionController::class)->group(function () {
        Route::get('transactions', 'transactions')->Middleware('admin');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'orders')->middleware('admin');
        Route::post('create-order', 'createOrder')->middleware('user');
    });
});