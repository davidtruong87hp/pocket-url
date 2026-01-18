<?php

namespace Tests\Unit\Helpers;

use App\Helpers\LocationHelper;
use Tests\TestCase;

class LocationHelperTest extends TestCase
{
    public function test_it_can_return_a_random_location()
    {
        $location = LocationHelper::random();

        $this->assertNotEmpty($location['country']);
        $this->assertNotEmpty($location['country_code']);
        $this->assertNotEmpty($location['region']);
        $this->assertNotEmpty($location['city']);
    }

    public function test_it_can_return_a_random_location_for_a_predefined_country(): void
    {
        $location = LocationHelper::randomFor('BR');

        $this->assertEquals('Brazil', $location['country']);
        $this->assertEquals('BR', $location['country_code']);
        $this->assertNotEmpty($location['region']);
        $this->assertNotEmpty($location['city']);
    }

    public function test_it_can_return_an_unknow_location_for_a_non_existent_country(): void
    {
        $location = LocationHelper::randomFor('non-existent-country');

        $this->assertEquals('Unknown', $location['country']);
        $this->assertEquals('Unknown', $location['region']);
        $this->assertEquals('Unknown', $location['city']);
    }

    public function test_it_can_return_a_random_location_for_a_predefined_region(): void
    {
        $location = LocationHelper::randomFromRegion('CN', 'Guangdong');

        $this->assertEquals('China', $location['country']);
        $this->assertEquals('Guangdong', $location['region']);
        $this->assertNotEmpty($location['city']);
    }

    public function test_it_can_return_an_unknow_location_for_a_non_existent_region(): void
    {
        $location = LocationHelper::randomFromRegion('CN', 'non-existent');

        $this->assertEquals('China', $location['country']);
        $this->assertEquals('CN', $location['country_code']);
        $this->assertEquals('non-existent', $location['region']);
        $this->assertEquals('Unknown', $location['city']);

    }
}
