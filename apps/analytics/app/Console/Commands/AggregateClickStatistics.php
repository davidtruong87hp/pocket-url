<?php

namespace App\Console\Commands;

use App\DTOs\SaveClickStatisticDto;
use App\Services\ClickStatisticService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Swoole\Coroutine;
use Swoole\Runtime;
use Throwable;

class AggregateClickStatistics extends Command
{
    protected $signature = 'analytics:aggregate {--date=yesterday}';

    protected $description = 'Aggregate click statistics for a specific date';

    private ClickStatisticService $clickStatisticService;

    public function handle(
        ClickStatisticService $clickStatisticService
    ): int {
        $this->clickStatisticService = $clickStatisticService;

        $date = $this->option('date') === 'yesterday'
            ? Carbon::yesterday()
            : Carbon::parse($this->option('date'));

        $this->info("Aggregating click statistics for {$date->toDateString()}...");

        // Enable Swoole coroutines
        Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

        $shortCodes = $clickStatisticService->getUniqueShortcodesByDate($date);

        if ($shortCodes->isEmpty()) {
            $this->info('No clicks found for this date.');

            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($shortCodes->count());
        $bar->start();
        $this->newLine(2);

        foreach ($shortCodes as $shortCode) {
            $this->info("🔗 Processing: {$shortCode}");

            $startTime = microtime(true);
            $this->aggregateForShortCode($shortCode, $date);
            $duration = round(microtime(true) - $startTime, 2);

            $this->info("✅ Completed in {$duration}s\n");
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('🎉 All aggregations complete!');

        return Command::SUCCESS;
    }

    private function aggregateForShortCode(string $shortCode, Carbon $date): void
    {
        Coroutine\run(function () use ($shortCode, $date) {
            $tasks = [
                'click_counts' => fn () => $this->clickStatisticService->aggregateTotalClicks($shortCode, $date),
                'top_countries' => fn () => $this->clickStatisticService->aggregateTopCountries($shortCode, $date),
                'top_cities' => fn () => $this->clickStatisticService->aggregateTopCities($shortCode, $date),
                'top_referrers' => fn () => $this->clickStatisticService->aggregateTopReferrers($shortCode, $date),
                'top_devices' => fn () => $this->clickStatisticService->aggregateTopDevices($shortCode, $date),
                'top_browsers' => fn () => $this->clickStatisticService->aggregateTopBrowsers($shortCode, $date),
                'top_platforms' => fn () => $this->clickStatisticService->aggregateTopPlatforms($shortCode, $date),
                'hourly_distribution' => fn () => $this->clickStatisticService->aggregateHourlyDistribution($shortCode, $date),
            ];

            $aggregatedData = $this->runConcurrent($tasks);

            $this->saveAggregatedData($aggregatedData, $shortCode, $date);
        });
    }

    private function runConcurrent(array $tasks): array
    {
        $channel = new Coroutine\Channel(count($tasks));

        foreach ($tasks as $name => $task) {
            $this->startTask($task, $name, $channel);
        }

        // Collect all results
        $aggregatedData = [];

        // Wait for all tasks to complete
        for ($i = 0; $i < count($tasks); $i++) {
            $result = $channel->pop();

            if ($result['success']) {
                $aggregatedData[$result['name']] = $result['data'];

                $this->info("  ✅ {$result['name']} completed in {$result['duration']}s");
            } else {
                $this->error("  ❌ {$result['name']} failed: {$result['error']}");
            }
        }

        return $aggregatedData;
    }

    private function startTask(callable $task, string $name, Coroutine\Channel $channel): void
    {
        go(function () use ($name, $task, $channel) {
            try {
                $startTime = microtime(true);
                $data = $task();
                $duration = round(microtime(true) - $startTime, 2);

                $channel->push([
                    'success' => true,
                    'name' => $name,
                    'duration' => $duration,
                    'data' => $data,
                ]);
            } catch (Throwable $e) {
                $channel->push([
                    'success' => false,
                    'name' => $name,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    private function saveAggregatedData(array $aggregatedData, string $shortCode, Carbon $date): void
    {
        $dto = new SaveClickStatisticDto(
            shortcode: $shortCode,
            date: $date,
            totalClicks: $aggregatedData['click_counts']['total_clicks'],
            uniqueClicks: $aggregatedData['click_counts']['unique_clicks'],
            botClicks: $aggregatedData['click_counts']['bot_clicks'],
            mobileClicks: $aggregatedData['click_counts']['mobile_clicks'],
            topCountries: $aggregatedData['top_countries'],
            topCities: $aggregatedData['top_cities'],
            topReferrers: $aggregatedData['top_referrers'],
            topDevices: $aggregatedData['top_devices'],
            topBrowsers: $aggregatedData['top_browsers'],
            topPlatforms: $aggregatedData['top_platforms'],
            hourlyDistribution: $aggregatedData['hourly_distribution'],
        );

        $this->clickStatisticService->save($dto);
    }
}
