<?php

namespace Tests\Feature\Api;

use App\Models\ClickStatistic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetLinkAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.analytics.api_key' => 'test-api-key']);
    }

    public function test_it_returns_analytics_for_specific_link(): void
    {
        ClickStatistic::factory()->create([
            'shortcode' => 'abc123',
            'date' => '2026-01-20',
            'total_clicks' => 100,
        ]);

        ClickStatistic::factory()->create([
            'shortcode' => 'xyz789',
            'date' => '2026-01-20',
            'total_clicks' => 50,
        ]);

        $response = $this->withHeader('X-API-KEY', 'test-api-key')
            ->getJson('/api/links/abc123/analytics?startDate=2026-01-20&endDate=2026-01-20');

        $response->assertStatus(200);

        $summary = $response->json()['summary'];

        $this->assertEquals(100, $summary['totalEngagements']);
    }
}
