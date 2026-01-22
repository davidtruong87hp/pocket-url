<?php

namespace App\Services\Analytics;

use App\Models\ClickStatistic;
use Illuminate\Database\Eloquent\Collection;

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
        $this->query->where('user_id', $userId);

        return $this;
    }

    public function forDateRange($start, $end): self
    {
        $this->query->whereBetween('date', [$start, $end]);

        return $this;
    }

    public function get(): Collection
    {
        return $this->query->get();
    }
}
