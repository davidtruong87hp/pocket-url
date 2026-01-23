<?php

namespace Tests\Feature\Api;

use App\Models\ClickStatistic;
use App\Models\LinkClick;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetUserAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.analytics.api_key' => 'test-api-key']);
    }

    public function test_it_returns_analytics_for_specific_user(): void
    {
        $linkClick = LinkClick::factory()->create([
            'user_id' => 1,
        ]);

        ClickStatistic::factory()->create([
            'shortcode' => $linkClick->shortcode,
            'date' => '2026-01-20',
            'total_clicks' => 100,
        ]);

        ClickStatistic::factory()->create([
            'shortcode' => 'xyz789',
            'date' => '2026-01-20',
            'total_clicks' => 50,
        ]);

        $response = $this->withHeader('X-API-KEY', 'test-api-key')
            ->getJson('/api/users/1/analytics?startDate=2026-01-20&endDate=2026-01-20');

        $response->assertStatus(200);

        $summary = $response->json()['summary'];

        $this->assertEquals(100, $summary['totalEngagements']);
    }

    public function test_it_only_accepts_custom_date_range_within_90_days()
    {
        $response = $this->withHeader('X-API-KEY', 'test-api-key')
            ->getJson('/api/users/1/analytics?startDate=2025-01-20&endDate=2026-01-20');

        $response->assertStatus(422);

        $this->assertEquals('The end date must be within 90 days of the start date.', $response->json()['errors']['endDate'][0]);
    }
}
