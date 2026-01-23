<?php

namespace App\Services\Analytics\Transformers;

use App\Services\Analytics\Transformers\Traits\AggregatesJsonColumns;
use Illuminate\Support\Collection;

class ReferrerBreakdownTransformer
{
    use AggregatesJsonColumns;

    public function transform(Collection $stats): array
    {
        return $this->aggregateJsonColumn($stats, 'top_referrers', 'domain');
    }
}
