<?php

namespace App\Console\Commands;

use App\Services\AnalyticsService;
use Illuminate\Console\Command;

class InvalidateAnalyticsCacheCommand extends Command
{
    protected $signature = 'analytics:cache:clear';

    protected $description = 'Invalidate analytics cache';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsService $analyticsService): int
    {
        $analyticsService->invalidateAllCache();

        $this->info('Analytics cache cleared.');

        return self::SUCCESS;
    }
}
