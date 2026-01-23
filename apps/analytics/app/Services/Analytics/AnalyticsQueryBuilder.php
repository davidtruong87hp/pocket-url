<?php

namespace App\Services\Analytics;

use App\Models\ClickStatistic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AnalyticsQueryBuilder
{
    private $query;

    public function __construct()
    {
        $this->query = ClickStatistic::query();
    }

    public function forShortcode(string $shortcode): self
    {
        $this->query->where('shortcode', $shortcode);

        return $this;
    }

    public function forUserId(int $userId): self
    {
        $this->query
            ->whereExists(function (Builder $query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('link_clicks')
                    ->whereColumn('click_statistics.shortcode', '=', 'link_clicks.shortcode')
                    ->where('link_clicks.id', '=', $userId);
            });

        return $this;
    }

    public function forDateRange($start, $end): self
    {
        $this->query->whereBetween('date', [$start, $end]);

        return $this;
    }

    public function get($columns = ['*']): Collection
    {
        return $this->query->select($columns)->get();
    }
}
