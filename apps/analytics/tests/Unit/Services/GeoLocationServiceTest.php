<?php

namespace Tests\Unit\Services;

use App\Services\GeoLocationService;
use Tests\TestCase;

class GeoLocationServiceTest extends TestCase
{
    private GeoLocationService $geoLocationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->geoLocationService = $this->app->make(GeoLocationService::class);
    }

    public function test_lookup_with_empty_ip_address()
    {
        $this->assertEquals([
            'country_code' => null,
            'country_name' => null,
            'region' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
        ], $this->geoLocationService->lookup(''));
    }

    public function test_lookup_with_private_ip_address()
    {
        $this->assertEquals([
            'country_code' => null,
            'country_name' => null,
            'region' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
        ], $this->geoLocationService->lookup('127.0.0.1'));
    }

    public function test_lookup_with_valid_ip_address()
    {
        $result = $this->geoLocationService->lookup('8.8.8.8');

        $this->assertNotEmpty($result['country_code']);
        $this->assertNotEmpty($result['country_name']);
        $this->assertNotEmpty($result['region']);
        $this->assertNotEmpty($result['city']);
        $this->assertNotEmpty($result['latitude']);
        $this->assertNotEmpty($result['longitude']);
    }
}
