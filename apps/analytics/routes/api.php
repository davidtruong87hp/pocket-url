<?php

use App\Http\Controllers\GetLinkAnalyticsController;
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
});
