<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CorsHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CorsHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        putenv('CORS_ALLOWED_ORIGINS');
    }

    protected function tearDown(): void
    {
        putenv('CORS_ALLOWED_ORIGINS');
        parent::tearDown();
    }

    #[DataProvider('developmentEnvironmentProvider')]
    public function test_it_returns_default_origins_in_development($environment)
    {
        $origins = CorsHelper::getAllowedOrigins($environment);

        $this->assertContains('http://localhost:5173', $origins);
    }

    #[DataProvider('developmentEnvironmentProvider')]
    public function test_it_merges_custom_origins_in_development($environment)
    {
        putenv('CORS_ALLOWED_ORIGINS=http://localhost:9000,http://192.168.1.50:5173');

        $origins = CorsHelper::getAllowedOrigins($environment);

        $this->assertContains('http://localhost:5173', $origins);
        $this->assertContains('http://localhost:9000', $origins);
        $this->assertContains('http://192.168.1.50:5173', $origins);
    }

    #[DataProvider('productionEnvironmentProvider')]
    public function test_it_only_uses_configured_origins_in_non_development($environment)
    {
        putenv('CORS_ALLOWED_ORIGINS=https://pocket-url.com');

        $origins = CorsHelper::getAllowedOrigins($environment);

        $this->assertCount(1, $origins);
        $this->assertContains('https://pocket-url.com', $origins);
        $this->assertNotContains('http://localhost:5173', $origins);
    }

    public function test_it_validates_https_required_in_production()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Production origin must use HTTPS: http://pocket-url.com');

        putenv('CORS_ALLOWED_ORIGINS=http://pocket-url.com');

        CorsHelper::validateProductionOrigins('production');
    }

    // Data providers
    public static function developmentEnvironmentProvider(): array
    {
        return [
            'local environment' => ['local'],
            'development environment' => ['development'],
        ];
    }

    public static function productionEnvironmentProvider(): array
    {
        return [
            'production environment' => ['production'],
            'staging environment' => ['staging'],
        ];
    }
}
