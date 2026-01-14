<?php

namespace App\Jobs;

use App\Services\RabbitMQ\MessagePublisherInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class PublishCacheInvalidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [2, 5, 10]; // Seconds between retries

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $type,
        public string $shortcode,
        public array $metadata = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MessagePublisherInterface $publisher): void
    {
        $publisher->publish('cache.invalidation', [
            'type' => $this->type,
            'shortcode' => $this->shortcode,
            'metadata' => $this->metadata,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        logger()->error('Cache invalidation job failed permanently', [
            'type' => $this->type,
            'shortcode' => $this->shortcode,
            'error' => $exception->getMessage(),
        ]);
    }
}
