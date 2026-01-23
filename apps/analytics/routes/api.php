<?php

use App\Http\Controllers\GetLinkAnalyticsController;
use App\Http\Controllers\GetUserAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'service' => 'pocket-url-analytics-api',
    ]);
});

Route::middleware(['api_key'])->group(function () {
    // Per-link analytics
    Route::get('/links/{shortcode}/analytics', GetLinkAnalyticsController::class)->where('shortcode', '[a-zA-Z0-9]{6}+');

    // Per-user analytics
    Route::get('/users/{userId}/analytics', GetUserAnalyticsController::class)->where('userId', '[0-9]+');
});
