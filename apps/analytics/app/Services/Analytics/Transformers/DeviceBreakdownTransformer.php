<?php

namespace App\Services\Analytics\Transformers;

use App\Services\Analytics\Transformers\Traits\AggregatesJsonColumns;
use Illuminate\Database\Eloquent\Collection;

class DeviceBreakdownTransformer
{
    use AggregatesJsonColumns;

    public function transform(Collection $stats): array
    {
        $devices = $this->aggregateJsonColumn($stats, 'top_devices', 'type');
        $total = collect($devices)->sum('value');

        return collect($devices)->map(function ($item) use ($total) {
            return [
                'label' => $item['label'],
                'value' => $item['value'],
                'percentage' => $total > 0
                    ? round(($item['value'] / $total) * 100, 1)
                    : 0,
            ];
        })->toArray();
    }
}
