<?php

namespace Tests\Unit\Services\MessageQueue;

use App\Services\MessageQueue\Contracts\MessageHandlerInterface;
use App\Services\MessageQueue\Statistics;
use App\Services\MessageQueue\Worker;
use Mockery;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

class WorkerTest extends TestCase
{
    public function test_processes_job_successfully()
    {
        $stats = new Statistics;
        $worker = new Worker(0, null, $stats);

        $handler = $this->mock(MessageHandlerInterface::class);
        $handler->shouldReceive('handle')->once();

        $job = [
            'handler' => $handler,
            'message' => new AMQPMessage,
            'consumer_name' => 'test',
            'received_at' => microtime(true),
        ];

        $worker->processJob($job);

        $this->assertEquals(1, $stats->getTotalProcessed());
        $this->assertEquals(0, $stats->getTotalFailed());
    }

    public function test_handles_processing_failure()
    {
        $stats = new Statistics;
        $worker = new Worker(0, null, $stats);

        $handler = $this->mock(MessageHandlerInterface::class);
        $message = $this->mock(AMQPMessage::class);

        $handler->shouldReceive('handle')->andThrow(new \Exception('foo'));
        $handler->shouldReceive('handleFailure')->once()->with($message, Mockery::type(\Exception::class));

        $job = [
            'handler' => $handler,
            'message' => $message,
            'consumer_name' => 'test',
            'received_at' => microtime(true),
        ];

        $worker->processJob($job);

        $this->assertEquals(0, $stats->getTotalProcessed());
        $this->assertEquals(1, $stats->getTotalFailed());
    }
}
