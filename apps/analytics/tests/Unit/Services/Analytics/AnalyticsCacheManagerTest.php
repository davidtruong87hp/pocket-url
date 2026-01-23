<?php

namespace Tests\Unit\Services\Analytics;

use App\Services\Analytics\AnalyticsCacheManager;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AnalyticsCacheManagerTest extends TestCase
{
    private AnalyticsCacheManager $cacheManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheManager = new AnalyticsCacheManager;
        Cache::flush();
    }

    public function test_it_generates_cache_key()
    {
        $dateRange = [
            'start' => Carbon::parse('2026-01-16'),
            'end' => Carbon::parse('2026-01-22'),
        ];

        $key = $this->cacheManager->generateKey('link:shortcode', $dateRange);

        $this->assertStringContainsString('link:shortcode', $key);
        $this->assertStringContainsString('2026-01-16', $key);
        $this->assertStringContainsString('2026-01-22', $key);
    }

    public function test_it_calculates_ttl_for_historical_data()
    {
        $yesterday = Carbon::yesterday();

        $ttl = $this->cacheManager->calculateTTL($yesterday);

        $this->assertEquals(24 * 60 * 60, $ttl);
    }

    public function test_it_calculates_ttl_for_today()
    {
        $now = Carbon::now();

        $ttl = $this->cacheManager->calculateTTL($now);

        $this->assertEquals(5 * 60, $ttl);
    }

    public function test_it_remembers_caches_result()
    {
        $key = 'testKey';
        $tags = ['analytics', 'link:shortcode'];
        $callCount = 0;

        // First call - should execute callback
        $result1 = $this->cacheManager->remember($key, 60, $tags, function () use (&$callCount) {
            $callCount++;

            return ['foo' => 'bar'];
        });

        // Second call - should return cached result
        $result2 = $this->cacheManager->remember($key, 60, $tags, function () use (&$callCount) {
            $callCount++;

            return ['foo' => 'bar'];
        });

        $this->assertEquals(1, $callCount);
        $this->assertEquals($result1, $result2);
    }

    public function test_it_flushs_tags_clears_cache()
    {
        if (! in_array(config('cache.default'), ['redis', 'memcached'])) {
            $this->markTestSkipped('Cache driver not supported');
        }

        $key = 'testKey';
        $tags = ['analytics', 'link:shortcode'];

        $this->cacheManager->remember($key, 60, $tags, function () {
            return ['foo' => 'bar'];
        });

        $this->cacheManager->flushTags($tags);

        $this->assertNull(Cache::tags($tags)->get($key));
    }
}
