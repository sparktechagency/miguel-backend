<?php

use App\Http\Controllers\Api\NotificationController;
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
        Route::post('update-profile', 'updateProfile');
        Route::get('profile', 'profile');
    });
});
Route::controller(ContactController::class)->group(function () {
    Route::post('contact', 'contact');
    Route::post('apply-for-artist', 'applyForArtist');
    Route::post('subscription', 'subscribe');
    Route::get('get-subscription', 'getSubscribe');
});
Route::middleware(['user','auth:sanctum'])->controller(WishlistController::class)->group(function () {
    Route::get('wishlist', 'wishlist');
    Route::post('create-wishlist/{songId}', 'createWishlist');
    Route::post('remove-wishlist/{songId}', 'removeWishlist');
});
Route::middleware(['user','auth:sanctum'])->controller(FollowController::class)->group(function () {
    Route::post('follow/{artistId}', 'createFollow');
    Route::get('follow', 'follow');
    Route::patch('unfollow/{artistId}', 'unfollow');
});
Route::middleware(['user','auth:sanctum'])->controller(SupportController::class)->group(function () {
    Route::post('support', 'support');
});
Route::middleware(['user','auth:sanctum'])->controller(PaymentController::class)->group(function () {
    Route::post('create-payment-intent', 'createPaymentIntent');
});

/* ---------------------------------- admin ------------------------------*/

    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->middleware(['admin','auth:sanctum']);
    });
    Route::controller(BannerController::class)->group(function () {
        Route::get('banner', 'banner');
        Route::post('create-banner', 'createBanner')->middleware(['admin','auth:sanctum']);
    });
    Route::controller(UserController::class)->middleware(['admin','auth:sanctum'])->group(function () {
        Route::get('users', 'users');
        Route::patch('update-banned-status/{userId}', 'updateBannedStatus');
    });
    Route::controller(CategoryController::class)->group(function () {
        //genre
        Route::get('genre', 'genre');
        Route::post('create-genre', 'createGenre')->middleware(['admin','auth:sanctum']);
        Route::delete('delete-genre/{genreId}', 'deleteGenre')->middleware(['admin','auth:sanctum']);

        // BNP
        Route::get('bpm', 'bpm');
        Route::post('create-bpm', 'createBpm')->middleware(['admin','auth:sanctum']);
        Route::delete('delete-bpm/{bnpId}', 'deleteBpm')->middleware(['admin','auth:sanctum']);

        // Key
        Route::get('key', 'key');
        Route::post('create-key', 'createKey')->middleware(['admin','auth:sanctum']);
        Route::delete('delete-key/{keyId}', 'deleteKey')->middleware(['admin','auth:sanctum']);

        // License
        Route::get('license', 'license');
        Route::post('create-license', 'createLicense')->middleware(['admin','auth:sanctum']);
        Route::delete('delete-license/{licenseId}', 'deleteLicense')->middleware(['admin','auth:sanctum']);

        // Type
        Route::get('type', 'type');
        Route::post('create-type', 'createType')->middleware(['admin','auth:sanctum']);
        Route::delete('delete-type/{typeId}', 'deleteType')->middleware(['admin','auth:sanctum']);
    });
    Route::controller(ArtistController::class)->group(function () {
        Route::get('artist', 'artist');
        Route::get('artist-detail/{slug}', 'artistDetail');

        Route::post('create-artist', 'createArtist')->Middleware(['admin','auth:sanctum']);
        Route::put('update-artist/{artistId}', 'updateArtist')->Middleware(['admin','auth:sanctum']);
        Route::delete('delete-artist/{artistId}', 'deleteArtist')->Middleware(['admin','auth:sanctum']);
        Route::put('top-artist/{artistId}',  'topArtist')->middleware(['admin', 'auth:sanctum']);
    });
    Route::controller(SongController::class)->group(function () {
        Route::get('song', 'song');
        Route::get('song-details/{songId}', 'songDetails');
        Route::get('publish-song', 'publishSong');
        Route::get('latest-trending/{song_id}', 'latestTrending');

        Route::post('create-song', 'createSong')->Middleware(['admin','auth:sanctum']);
        Route::put('update-song/{songId}', 'updateSong')->Middleware(['admin','auth:sanctum']);
        Route::patch('published/{songId}', 'published')->Middleware(['admin','auth:sanctum']);
        Route::delete('delete-song/{songId}', 'deleteSong')->Middleware(['admin','auth:sanctum']);
    });
    Route::controller(TransactionController::class)->group(function () {
        Route::get('transactions', 'transactions')->Middleware(['admin','auth:sanctum']);
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'orders')->middleware(['admin','auth:sanctum']);
        Route::get('user-orders', 'userOrders')->middleware(['user','auth:sanctum']);
        Route::get('order-details/{order_id}', 'orderDetails')->middleware(['user','auth:sanctum']);
        Route::post('create-order', 'createOrder')->middleware(['user','auth:sanctum']);
        Route::post('custom-order/{artist_id}', 'customOrder')->middleware(['user','auth:sanctum']);
    });

Route::group(['middleware' => ['auth:sanctum','user'], 'controller' => NotificationController::class], function () {
    Route::get('notifications', 'notifications');
    Route::post('notifications-read/{id}', 'markAsRead');
    Route::post('notifications-read-all','notificationReadAll');
});
