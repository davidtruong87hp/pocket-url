<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureApiRateLimiting();
    }

    protected function configureApiRateLimiting(): void
    {
        RateLimiter::for('api-tiered', function (Request $request) {
            /** @var \App\Models\User|null $user */
            $user = $request->user();

            if (! $user) {
                return Limit::perMinute(5)->by($request->ip());
            }

            return [
                Limit::perMinute($user->getRateLimitPerMinute())
                    ->by($user->id)
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'message' => 'Rate limit exceeded',
                            'retry_after' => $headers['Retry-After'] ?? 60,
                            'limit_type' => 'every_minute',
                        ], 429);
                    }),
                Limit::perHour($user->getRateLimitPerHour())
                    ->by($user->id)
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'message' => 'Hourly rate limit exceeded',
                            'retry_after' => $headers['Retry-After'],
                            'limit_type' => 'hourly',
                        ], 429);
                    }),
                ...($user->getRateLimitPerDay() ? [
                    Limit::perDay($user->getRateLimitPerDay())
                        ->by($user->id)] : []
                ),
            ];
        });
    }
}
