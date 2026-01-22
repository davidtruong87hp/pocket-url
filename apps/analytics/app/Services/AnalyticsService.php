<?php

namespace App\Services;

use App\Services\Analytics\AnalyticsQueryBuilder;
use App\Services\Analytics\AnalyticsResponseBuilder;
use App\Services\Analytics\DateRangeParser;

class AnalyticsService
{
    public function __construct(
        private DateRangeParser $dateParser,
        private AnalyticsQueryBuilder $queryBuilder,
        private AnalyticsResponseBuilder $responseBuilder
    ) {}

    public function getLinkAnalytics(string $shortcode, array $params): array
    {
        $dateRange = $this->dateParser->parse($params);

        $stats = $this->queryBuilder
            ->forShortcode($shortcode)
            ->forDateRange($dateRange['start'], $dateRange['end'])
            ->get();

        return $this->responseBuilder->build($stats, $dateRange);
    }
}
