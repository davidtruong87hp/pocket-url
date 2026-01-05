<?php

namespace Tests\Feature;

use Tests\TestCase;

class CorsIntegrationTest extends TestCase
{
    const TEST_ENDPOINT = '/api/health';

    const TEST_ORIGIN_VALID = 'http://localhost:5173';

    const TEST_ORIGIN_INVALID = 'http://malicious-site.com';

    public function test_it_allows_cors_for_configured_origin()
    {
        config(['cors.allowed_origins' => [self::TEST_ORIGIN_VALID]]);

        $response = $this->withHeaders([
            'Origin' => self::TEST_ORIGIN_VALID,
        ])->getJson(self::TEST_ENDPOINT);

        $response->assertHeader('Access-Control-Allow-Origin', self::TEST_ORIGIN_VALID);
        $response->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function test_it_blocks_cors_for_non_configured_origin()
    {
        config(['cors.allowed_origins' => [self::TEST_ORIGIN_VALID]]);

        $response = $this->withHeaders([
            'Origin' => self::TEST_ORIGIN_INVALID,
        ])->getJson(self::TEST_ENDPOINT);

        // If malicious origin gets CORS headers, test fails
        $allowedOrigin = $response->headers->get('Access-Control-Allow-Origin');

        if ($allowedOrigin !== null) {
            $this->assertNotEquals(self::TEST_ORIGIN_INVALID, $allowedOrigin);
        }
    }

    public function test_it_handles_preflight_correctly_for_allowed_origin()
    {
        config(['cors.allowed_origins' => [self::TEST_ORIGIN_VALID]]);

        $response = $this->withHeaders([
            'Origin' => self::TEST_ORIGIN_VALID,
            'Access-Control-Request-Method' => 'GET',
            'Access-Control-Request-Headers' => 'Content-Type',
        ])->getJson(self::TEST_ENDPOINT);

        $response->assertHeader('Access-Control-Allow-Origin', self::TEST_ORIGIN_VALID);
        $response->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function test_it_rejects_preflight_for_non_configured_origin()
    {
        config(['cors.allowed_origins' => [self::TEST_ORIGIN_VALID]]);

        $response = $this->withHeaders([
            'Origin' => self::TEST_ORIGIN_INVALID,
            'Access-Control-Request-Method' => 'GET',
        ])->options(self::TEST_ENDPOINT);

        $allowedOrigin = $response->headers->get('Access-Control-Allow-Origin');

        if ($allowedOrigin !== null) {
            $this->assertNotEquals(self::TEST_ORIGIN_INVALID, $allowedOrigin);
        }
    }
}
