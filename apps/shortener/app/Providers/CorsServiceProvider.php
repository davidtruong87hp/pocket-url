<?php

namespace App\Providers;

use App\Helpers\CorsHelper;
use Illuminate\Support\ServiceProvider;

class CorsServiceProvider extends ServiceProvider
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
        if ($this->app->environment('production')) {
            $this->validateCorsConfiguration();
        }
    }

    protected function validateCorsConfiguration(): void
    {
        try {
            $environment = $this->app->environment();
            CorsHelper::validateProductionOrigins($environment);
        } catch (\RuntimeException $e) {
            // Log error but don't crash the app
            logger()->critical('CORS Configuration Error: '.$e->getMessage(), [
                'exception' => $e,
                'origins' => config('cors.allowed_origins'),
            ]);

            // Optionally throw in production to prevent deployment
            if (config('app.strict_cors_validation', false)) {
                throw $e;
            }
        }
    }
}
