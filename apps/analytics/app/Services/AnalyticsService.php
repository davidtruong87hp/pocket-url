<?php

namespace App\Services;

use App\Services\Analytics\AnalyticsCacheManager;
use App\Services\Analytics\AnalyticsQueryBuilder;
use App\Services\Analytics\AnalyticsResponseBuilder;
use App\Services\Analytics\DateRangeParser;

class AnalyticsService
{
    public function __construct(
        private DateRangeParser $dateParser,
        private AnalyticsQueryBuilder $queryBuilder,
        private AnalyticsResponseBuilder $responseBuilder,
        private AnalyticsCacheManager $cacheManager
    ) {}

    public function getLinkAnalytics(string $shortcode, array $params): array
    {
        $dateRange = $this->dateParser->parse($params);
        $cacheKey = $this->cacheManager->generateKey("link:{$shortcode}", $dateRange, $params);
        $ttl = $this->cacheManager->calculateTTL($dateRange['end']);

        return $this->cacheManager->remember(
            $cacheKey,
            $ttl,
            ['analytics', "link:{$shortcode}"],
            function () use ($shortcode, $dateRange) {
                $stats = $this->queryBuilder
                    ->forShortcode($shortcode)
                    ->forDateRange($dateRange['start'], $dateRange['end'])
                    ->get();

                return $this->responseBuilder->build($stats, $dateRange);
            }
        );
    }

    public function invalidateCache(?string $shortcode = null, ?int $userId = null)
    {
        $tags = ['analytics'];

        if ($shortcode) {
            $tags[] = "link:$shortcode";
        }

        if ($userId) {
            $tags[] = "user:$userId";
        }

        $this->cacheManager->flushTags($tags);
    }

    public function invalidateAllCache()
    {
        $this->cacheManager->flushTags(['analytics']);
    }
}
