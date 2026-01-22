<?php

namespace App\Services\Analytics;

use App\Services\Analytics\Transformers\DeviceBreakdownTransformer;
use App\Services\Analytics\Transformers\LocationBreakdownTransformer;
use App\Services\Analytics\Transformers\ReferrerBreakdownTransformer;
use App\Services\Analytics\Transformers\StatsCardsTransformer;
use App\Services\Analytics\Transformers\SummaryTransformer;
use App\Services\Analytics\Transformers\TimelineTransformer;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsResponseBuilder
{
    public function __construct(
        private SummaryTransformer $summaryTransformer,
        private StatsCardsTransformer $statsCardsTransformer,
        private DeviceBreakdownTransformer $deviceTransformer,
        private ReferrerBreakdownTransformer $referrerTransformer,
        private TimelineTransformer $timelineTransformer,
        private LocationBreakdownTransformer $locationTransformer
    ) {}

    public function build(Collection $stats, array $dateRange): array
    {
        return [
            'summary' => $this->summaryTransformer->transform($stats, $dateRange),
            'stats' => $this->statsCardsTransformer->transform($stats, $dateRange),
            'deviceBreakdown' => $this->deviceTransformer->transform($stats),
            'referrerBreakdown' => $this->referrerTransformer->transform($stats),
            'timeline' => $this->timelineTransformer->transform($stats),
            'locationBreakdown' => $this->locationTransformer->transform($stats),
        ];
    }
}
