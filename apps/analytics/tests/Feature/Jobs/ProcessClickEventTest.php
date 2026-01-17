<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessClickEvent;
use App\Models\LinkClick;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessClickEventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_processes_click_event_without_referrer()
    {
        $clickData = [
            'shortcode' => $this->faker->lexify('??????'),
            'ip' => $this->faker->ipv4(),
            'userAgent' => $this->faker->userAgent(),
            'timestamp' => now(),
        ];

        ProcessClickEvent::dispatchSync($clickData);

        $this->assertDatabaseHas('link_clicks', [
            'shortcode' => $clickData['shortcode'],
            'ip_address' => $clickData['ip'],
            'user_agent' => $clickData['userAgent'],
        ]);
    }

    public function test_processes_click_event_with_referrer()
    {
        $clickData = [
            'shortcode' => $this->faker->lexify('??????'),
            'ip' => $this->faker->ipv4(),
            'userAgent' => $this->faker->userAgent(),
            'timestamp' => now(),
            'referer' => $this->faker->url(),
        ];

        ProcessClickEvent::dispatchSync($clickData);

        $this->assertDatabaseHas('link_clicks', [
            'shortcode' => $clickData['shortcode'],
            'ip_address' => $clickData['ip'],
            'user_agent' => $clickData['userAgent'],
            'referrer_url' => $clickData['referer'],
        ]);

        $click = LinkClick::where('shortcode', $clickData['shortcode'])->first();
        $this->assertNotEmpty($click->referrer_domain);
    }

    public function test_handles_exception()
    {
        $clickData = [
            'shortcode' => $this->faker->lexify('??????'),
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(), // Invalid field
            'timestamp' => now(),
            'referer' => $this->faker->url(),
        ];

        $this->expectException(\Exception::class);
        ProcessClickEvent::dispatchSync($clickData);
    }
}
