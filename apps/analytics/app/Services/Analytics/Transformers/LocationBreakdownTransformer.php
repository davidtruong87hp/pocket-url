<?php

namespace App\Services\Analytics\Transformers;

use App\Services\Analytics\Transformers\Traits\AggregatesJsonColumns;
use Illuminate\Database\Eloquent\Collection;

class LocationBreakdownTransformer
{
    use AggregatesJsonColumns;

    public function transform(Collection $stats): array
    {
        $countries = $this->aggregateJsonColumn($stats, 'top_countries');
        $cities = $this->aggregateJsonColumn($stats, 'top_cities', 'city');

        $totalCountries = collect($countries)->sum('value');
        $totalCities = collect($cities)->sum('value');

        return [
            'countries' => $this->formatForTable($countries, $totalCountries),
            'cities' => $this->formatForTable($cities, $totalCities),
        ];
    }

    private function formatForTable(array $data, int $total): array
    {
        return collect($data)->map(function ($item, $index) use ($total) {
            return [
                'rank' => $index + 1,
                'country' => $item['label'],
                'engagements' => $item['value'],
                'percentage' => $total > 0
                    ? round(($item['value'] / $total) * 100, 1)
                    : 0,
            ];
        })->toArray();
    }
}
