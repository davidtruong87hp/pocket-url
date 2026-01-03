<?php

namespace App\Services;

use App\Repositories\ShortcodePoolRepository;
use Illuminate\Support\Str;

class ShortcodePoolManager
{
    public const STATUS_LOW = 'low';

    public const STATUS_HEALTHY = 'healthy';

    public const STATUS_REFILLED = 'refilled';

    private int $targetSize;

    private int $minSize;

    private int $batchSize;

    public function __construct(private ShortcodePoolRepository $poolRepository)
    {
        $this->targetSize = config('shortener.pool.target_size');
        $this->minSize = config('shortener.pool.min_size');
        $this->batchSize = config('shortener.pool.refill_batch_size');
    }

    public function checkAndRefillPool(): array
    {
        $currentSize = $this->poolRepository->getPoolSize();

        if ($currentSize >= $this->minSize) {
            return [
                'status' => self::STATUS_HEALTHY,
                'current_size' => $currentSize,
                'refilled' => 0,
            ];
        }

        $needed = $this->targetSize - $currentSize;
        $inserted = $this->refill($needed);

        return [
            'status' => self::STATUS_REFILLED,
            'current_size' => $this->poolRepository->getPoolSize(),
            'refilled' => $inserted,
        ];
    }

    public function refill(int $count): int
    {
        $numberOfBatches = ceil($count / $this->batchSize);
        $totalInserted = 0;

        for ($i = 0; $i < $numberOfBatches; $i++) {
            $remaining = $count - $totalInserted;
            $currentBatchSize = min($remaining, $this->batchSize);

            $batch = $this->generateBatch($currentBatchSize);
            $inserted = $this->poolRepository->insertBatch($batch);

            $totalInserted += $inserted;
        }

        return $totalInserted;
    }

    public function getStats(): array
    {
        $currentSize = $this->poolRepository->getPoolSize();
        $percentFull = round($currentSize / $this->targetSize * 100, 2);

        return [
            'current_size' => $currentSize,
            'target_size' => $this->targetSize,
            'min_size' => $this->minSize,
            'percent_full' => $percentFull,
            'status' => $currentSize < $this->minSize ? self::STATUS_LOW : self::STATUS_HEALTHY,
            'needs_refill' => $currentSize < $this->minSize,
        ];
    }

    private function generateBatch(int $size): array
    {
        $batch = [];
        $length = config('shortener.shortcode.length');

        for ($i = 0; $i < $size; $i++) {
            $batch[] = [
                'shortcode' => Str::random($length),
            ];
        }

        return $batch;
    }
}
