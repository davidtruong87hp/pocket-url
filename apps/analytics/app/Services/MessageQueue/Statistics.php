<?php

namespace App\Services\MessageQueue;

/**
 * Tracks consumer statistics
 * Thread-safe for coroutine usage
 */
class Statistics
{
    private int $totalProcessed = 0;

    private int $totalFailed = 0;

    private array $processingTimes = [];

    private const MAX_SAMPLES = 1000;

    public function recordSuccess(float $processingTimeMs): void
    {
        $this->totalProcessed++;
        $this->processingTimes[] = $processingTimeMs;

        if (count($this->processingTimes) > self::MAX_SAMPLES) {
            array_shift($this->processingTimes);
        }
    }

    public function recordFailure(): void
    {
        $this->totalFailed++;
    }

    public function getTotalProcessed(): int
    {
        return $this->totalProcessed;
    }

    public function getTotalFailed(): int
    {
        return $this->totalFailed;
    }

    public function getSuccessRate(): float
    {
        $total = $this->totalProcessed + $this->totalFailed;

        if ($total > 0) {
            return round(($this->totalProcessed / $total) * 100, 2);
        }

        return 100;
    }

    public function getAverageProcessingTime(): float
    {
        if (empty($this->processingTimes)) {
            return 0;
        }

        return round(
            array_sum($this->processingTimes) / count($this->processingTimes),
            2
        );
    }

    public function toArray(): array
    {
        return [
            'total_processed' => $this->totalProcessed,
            'total_failed' => $this->totalFailed,
            'success_rate_pct' => $this->getSuccessRate(),
            'avg_processing_time_ms' => $this->getAverageProcessingTime(),
        ];
    }
}
