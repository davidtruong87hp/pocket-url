<?php

namespace App\Helpers;

class CorsHelper
{
    public static function getAllowedOrigins(?string $environment = null): array
    {
        $env = $environment ?? env('APP_ENV', 'production');

        if (self::isDevelopment($env)) {
            return array_unique(array_merge(
                self::getLocalDevelopmentOrigins(),
                self::getConfiguredOrigins(),
            ));
        }

        return self::getConfiguredOrigins();
    }

    public static function validateProductionOrigins(?string $environment = null): void
    {
        $environment = $environment ?? env('APP_ENV', 'production');

        if (! self::isProduction($environment)) {
            return;
        }

        $origins = self::getAllowedOrigins();

        if (empty($origins)) {
            throw new \RuntimeException('CORS_ALLOWED_ORIGINS must be configured in production!');
        }

        foreach ($origins as $origin) {
            if (! str_starts_with($origin, 'https://')) {
                throw new \RuntimeException("Production origin must use HTTPS: {$origin}");
            }
        }
    }

    private static function getConfiguredOrigins(): array
    {
        $origins = env('CORS_ALLOWED_ORIGINS', '');

        if (empty($origins)) {
            return [];
        }

        return array_filter(
            array_map(
                fn (string $origin) => rtrim(trim($origin), '/'),
                explode(',', $origins)
            )
        );
    }

    private static function getLocalDevelopmentOrigins(): array
    {
        return [
            'http://localhost:5173', // Vite default
            'http://localhost:5500', // VSCode Live Server
        ];
    }

    private static function isDevelopment(string $env): bool
    {
        return in_array($env, ['local', 'development']);
    }

    private static function isProduction(string $env): bool
    {
        return $env === 'production';
    }
}
