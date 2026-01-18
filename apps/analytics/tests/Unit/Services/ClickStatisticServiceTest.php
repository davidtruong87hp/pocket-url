<?php

namespace Tests\Unit\Services;

use App\Services\ClickStatisticService;
use Database\Factories\LinkClickFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ClickStatisticServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private ClickStatisticService $service;

    private string $targetShortCode;

    private Carbon $targetDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ClickStatisticService::class);
        $this->targetShortCode = $this->faker->lexify('??????');
        $this->targetDate = Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0);
    }

    public function test_aggregates_unique_total_clicks(): void
    {
        $this->createClicks([
            'ip_address' => $this->faker->ipv4,
        ], 5);

        $this->createClicks([
            'ip_address' => $this->faker->ipv6,
        ], 5);

        $result = $this->service->aggregateTotalClicks($this->targetShortCode, $this->targetDate);

        $this->assertEquals(2, $result['unique_clicks']);
        $this->assertEquals(10, $result['total_clicks']);
    }

    public function test_aggregates_bot_and_mobile_clicks(): void
    {
        $this->createClicks([
            'is_bot' => true,
            'is_mobile' => false,
        ], 5);

        $this->createClicks([
            'is_bot' => false,
            'is_mobile' => true,
        ], 3);

        $result = $this->service->aggregateTotalClicks($this->targetShortCode, $this->targetDate);

        $this->assertEquals(5, $result['bot_clicks']);
        $this->assertEquals(3, $result['mobile_clicks']);
        $this->assertEquals(8, $result['total_clicks']);
    }

    public function test_aggregates_top_countries(): void
    {
        $this->createClicks([
            'fromCountry' => 'US',
        ], 10);

        $this->createClicks([
            'fromCountry' => 'SG',
        ], 15);

        $this->createClicks([
            'fromCountry' => 'JP',
        ], 8);

        $result = $this->service->aggregateTopCountries($this->targetShortCode, $this->targetDate);

        $this->assertEquals(3, count($result));

        $this->assertEquals('SG', $result[0]['code']);
        $this->assertEquals(15, $result[0]['count']);

        $this->assertEquals('US', $result[1]['code']);
        $this->assertEquals(10, $result[1]['count']);

        $this->assertEquals('JP', $result[2]['code']);
        $this->assertEquals(8, $result[2]['count']);
    }

    public function test_aggregates_top_cities(): void
    {
        $this->createClicks([
            'fromCountry' => 'CA',
        ], 10);

        $this->createClicks([
            'fromCountry' => 'TH',
        ], 18);

        $result = $this->service->aggregateTopCities($this->targetShortCode, $this->targetDate);

        $this->assertArrayHasKey('city', $result[0]);
        $this->assertArrayHasKey('country', $result[0]);
        $this->assertArrayHasKey('count', $result[0]);
    }

    public function test_aggregates_top_referrers(): void
    {
        $this->createClicks([
            'referrer_domain' => 'google.com',
        ], 10);

        $this->createClicks([
            'referrer_domain' => 'bing.com',
        ], 18);

        $this->createClicks([
            'referrer_domain' => 'yahoo.com',
        ], 12);

        $result = $this->service->aggregateTopReferrers($this->targetShortCode, $this->targetDate, 2);

        $this->assertEquals(2, count($result));

        $this->assertEquals('bing.com', $result[0]['domain']);
        $this->assertEquals(18, $result[0]['count']);

        $this->assertEquals('yahoo.com', $result[1]['domain']);
        $this->assertEquals(12, $result[1]['count']);
    }

    public function test_aggregates_top_devices(): void
    {
        $this->createClicks([
            'device_type' => 'desktop',
        ], 10);

        $this->createClicks([
            'device_type' => 'mobile',
        ], 18);

        $this->createClicks([
            'device_type' => 'tablet',
        ], 12);

        $result = $this->service->aggregateTopDevices($this->targetShortCode, $this->targetDate);

        $this->assertEquals(3, count($result));

        $this->assertEquals('mobile', $result[0]['type']);
        $this->assertEquals(18, $result[0]['count']);

        $this->assertEquals('tablet', $result[1]['type']);
        $this->assertEquals(12, $result[1]['count']);

        $this->assertEquals('desktop', $result[2]['type']);
        $this->assertEquals(10, $result[2]['count']);
    }

    public function test_aggregates_top_browsers(): void
    {
        $this->createClicks([
            'browser_name' => 'Chrome',
        ], 10);

        $this->createClicks([
            'browser_name' => 'Firefox',
        ], 18);

        $this->createClicks([
            'browser_name' => 'Safari',
        ], 12);

        $result = $this->service->aggregateTopBrowsers($this->targetShortCode, $this->targetDate);

        $this->assertEquals(3, count($result));

        $this->assertEquals('Firefox', $result[0]['name']);
        $this->assertEquals(18, $result[0]['count']);

        $this->assertEquals('Safari', $result[1]['name']);
        $this->assertEquals(12, $result[1]['count']);

        $this->assertEquals('Chrome', $result[2]['name']);
        $this->assertEquals(10, $result[2]['count']);
    }

    public function test_aggregates_top_operating_systems(): void
    {
        $this->createClicks([
            'os_name' => 'Windows',
        ], 10);

        $this->createClicks([
            'os_name' => 'Linux',
        ], 18);

        $this->createClicks([
            'os_name' => 'macOS',
        ], 12);

        $result = $this->service->aggregateTopPlatforms($this->targetShortCode, $this->targetDate);

        $this->assertEquals('Linux', $result[0]['name']);
        $this->assertEquals(18, $result[0]['count']);

        $this->assertEquals('macOS', $result[1]['name']);
        $this->assertEquals(12, $result[1]['count']);

        $this->assertEquals('Windows', $result[2]['name']);
        $this->assertEquals(10, $result[2]['count']);
    }

    public function test_aggregates_hourly_distribution(): void
    {
        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j <= $i; $j++) {
                LinkClickFactory::new([
                    'shortcode' => $this->targetShortCode,
                    'clicked_at' => $this->targetDate->clone()->addHours($i),
                ])->create();
            }
        }

        $result = $this->service->aggregateHourlyDistribution($this->targetShortCode, $this->targetDate);

        $this->assertEquals(range(1, 24), $result);

    }

    private function createClicks(array $attributes = [], int $count = 1): void
    {
        $fromCountry = $attributes['fromCountry'] ?? '';
        $fromRegion = $attributes['fromRegion'] ?? '';

        unset($attributes['fromCountry'], $attributes['fromRegion']);

        LinkClickFactory::new($attributes)
            ->forShortCode($this->targetShortCode)
            ->onDate($this->targetDate)
            ->fromCountry($fromCountry)
            ->fromRegion($fromCountry, $fromRegion)
            ->count(count: $count)->create();
    }
}
