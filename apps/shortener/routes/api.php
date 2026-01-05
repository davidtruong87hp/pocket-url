<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Link\CreateShortenedUrlController;
use App\Http\Controllers\Api\Link\DeleteShortenedUrlController;
use App\Http\Controllers\Api\Link\ListShortenedUrlController;
use App\Http\Controllers\Api\Link\UpdateShortenedUrlController;
use App\Http\Controllers\Api\Link\ViewShortenedUrlController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware(['auth:sanctum', 'throttle:api-tiered'])->group(function () {
    Route::get('/profile', ProfileController::class);
    Route::post('/logout', LogoutController::class);

    Route::prefix('links')->group(function () {
        Route::post('/', CreateShortenedUrlController::class);
        Route::get('/', ListShortenedUrlController::class);

        Route::prefix('{shortUrl}')->where(['shortUrl' => '.*'])->group(function () {
            Route::get('/', ViewShortenedUrlController::class);
            Route::put('/', UpdateShortenedUrlController::class);
            Route::delete('/', DeleteShortenedUrlController::class);
        });
    });
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'service' => 'pocket-url-shortener-api',
    ]);
});
