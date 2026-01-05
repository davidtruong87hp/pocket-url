<?php

namespace Tests\Feature\Providers;

use App\Providers\CorsServiceProvider;
use Tests\TestCase;

class CorsServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear any exisiting CORS config
        putenv('CORS_ALLOWED_ORIGINS');
        putenv('STRICT_CORS_VALIDATION');
    }

    protected function tearDown(): void
    {
        putenv('CORS_ALLOWED_ORIGINS');
        putenv('STRICT_CORS_VALIDATION');
        parent::tearDown();
    }

    public function test_it_boots_successfully_in_development()
    {
        $this->app['env'] = 'local';

        $provider = new CorsServiceProvider($this->app);
        $provider->boot();

        // No exception thrown
        $this->assertTrue(true);
    }

    public function test_it_boots_successfully_in_production_with_valid_config()
    {
        $this->app['env'] = 'production';

        putenv('CORS_ALLOWED_ORIGINS=https://pocket-url.com,https://api.pocket-url.com');

        $provider = new CorsServiceProvider($this->app);
        $provider->boot();

        // No exception thrown
        $this->assertTrue(true);
    }

    public function test_it_not_throw_in_production_with_invalid_config_when_strict_mode_off()
    {
        $this->app['env'] = 'production';
        putenv('CORS_ALLOWED_ORIGINS=');
        config(['app.strict_cors_validation' => false]);

        $provider = new CorsServiceProvider($this->app);
        $provider->boot();

        // No exception thrown
        $this->assertTrue(true);
    }

    public function test_it_throws_exception_when_strict_validation_enabled_and_origins_empty()
    {
        $this->expectException(\RuntimeException::class);

        $this->app['env'] = 'production';
        putenv('CORS_ALLOWED_ORIGINS=');
        config(['app.strict_cors_validation' => true]);

        $provider = new CorsServiceProvider($this->app);
        $provider->boot();
    }

    public function test_it_throws_when_http_used_in_production_with_strict_mode()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Production origin must use HTTPS: http://pocket-url.com');

        $this->app['env'] = 'production';
        putenv('CORS_ALLOWED_ORIGINS=http://pocket-url.com');
        config(['app.strict_cors_validation' => true]);

        $provider = new CorsServiceProvider($this->app);
        $provider->boot();
    }

    public function test_it_does_not_validate_in_non_production_environments()
    {
        $environments = ['development', 'testing', 'local', 'staging'];

        foreach ($environments as $environment) {
            $this->app['env'] = $environment;

            putenv('CORS_ALLOWED_ORIGINS=http://pocket-url.com');
            config(['app.strict_cors_validation' => true]);

            $provider = new CorsServiceProvider($this->app);
            $provider->boot();
        }

        // No exception thrown
        $this->assertTrue(true);
    }
}
