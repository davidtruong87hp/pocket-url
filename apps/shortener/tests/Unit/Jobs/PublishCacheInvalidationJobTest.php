<?php

namespace Tests\Unit\Jobs;

use App\Jobs\PublishCacheInvalidationJob;
use App\Services\RabbitMQ\MessagePublisherInterface;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class PublishCacheInvalidationJobTest extends TestCase
{
    public function test_job_publishes_message_with_correct_data(): void
    {
        $publisher = $this->mock(MessagePublisherInterface::class);
        $publisher->shouldReceive('publish')
            ->once()
            ->with('cache.invalidation', Mockery::on(function (array $data) {
                return $data['type'] === 'SHORTENED_URL_CREATED'
                    && $data['shortcode'] === 'abcDEF'
                    && isset($data['timestamp'])
                    && $data['metadata']['original_url'] === 'https://example.com';
            }));

        $this->app->instance(MessagePublisherInterface::class, $publisher);

        $job = new PublishCacheInvalidationJob(
            'SHORTENED_URL_CREATED',
            'abcDEF',
            ['original_url' => 'https://example.com']
        );

        $job->handle($publisher);
    }

    public function test_job_has_correct_retry_configuration(): void
    {
        $job = new PublishCacheInvalidationJob('SHORTENED_URL_UPDATED', 'abcDEF');
        $this->assertEquals(3, $job->tries);
        $this->assertEquals([2, 5, 10], $job->backoff);
    }

    public function test_job_is_serializable(): void
    {
        $job = new PublishCacheInvalidationJob(
            'SHORTENED_URL_UPDATED',
            'abcDEF',
            ['original_url' => 'https://example.com']
        );

        $serialized = serialize($job);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(PublishCacheInvalidationJob::class, $unserialized);
        $this->assertEquals('SHORTENED_URL_UPDATED', $unserialized->type);
        $this->assertEquals('abcDEF', $unserialized->shortcode);
        $this->assertEquals(['original_url' => 'https://example.com'], $unserialized->metadata);
    }

    public function test_failed_method_log_error(): void
    {
        Log::spy();

        $job = new PublishCacheInvalidationJob(
            'SHORTENED_URL_DELETED',
            'abcDEF'
        );

        $exception = new \Exception('Test exception');
        $job->failed($exception);

        Log::shouldHaveReceived('error')->once();
    }
}
