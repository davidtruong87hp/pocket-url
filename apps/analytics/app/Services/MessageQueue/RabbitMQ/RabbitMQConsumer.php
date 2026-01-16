<?php

namespace App\Services\MessageQueue\RabbitMQ;

use App\Services\MessageQueue\Contracts\MessageConsumerInterface;
use App\Services\MessageQueue\Contracts\MessageHandlerInterface;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use Throwable;

class RabbitMQConsumer implements MessageConsumerInterface
{
    private bool $running = true;

    private int $retryCount = 0;

    private const MAX_RETRIES = 5;

    public function __construct(
        private string $name,
        private array $config,
        private RabbitMQConnectionPool $connectionPool,
        private Channel $jobChannel,
        private MessageHandlerInterface $messageHandler
    ) {}

    public function start(): void
    {
        Log::info("Starting RabbitMQ consumer {$this->name}");

        while ($this->running && $this->retryCount < self::MAX_RETRIES) {
            try {
                $this->consumeLoop();
                break;
            } catch (Throwable $e) {
                $this->handleError($e);
            }
        }

        if ($this->retryCount >= self::MAX_RETRIES) {
            Log::error("Failed to start RabbitMQ consumer {$this->name} after {$this->retryCount} retries");
        }
    }

    public function stop(): void
    {
        $this->running = false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    private function consumeLoop(): void
    {
        $connection = $this->connectionPool->get(5.0);

        if (! $connection) {
            throw new \RuntimeException('Failed to get RabbitMQ connection from pool');
        }

        try {
            $channel = new RabbitMQChannel($connection, $this->config);
            $channel->consume(fn (AMQPMessage $msg) => $this->pushToJobChannel($msg));

            Log::info("Consumer {$this->name} ready", [
                'exchange' => $channel->getExchangeName(),
                'queue' => $channel->getQueueName(),
            ]);

            $this->waitForMessages($channel);

            $channel->close();
            $this->connectionPool->put($connection);
        } catch (Throwable $e) {
            $this->connectionPool->put($connection);
            throw $e;
        }
    }

    private function pushToJobChannel(AMQPMessage $msg): void
    {
        Log::info("{$this->name} received message");

        $pushed = $this->jobChannel->push([
            'message' => $msg,
            'consumer_name' => $this->name,
            'handler' => $this->messageHandler,
            'received_at' => microtime(true),
        ], 0.1);

        if (! $pushed) {
            Log::warning("{$this->name}: Job channel full, requeueing");
            $msg->nack(true);
        }
    }

    private function waitForMessages(RabbitMQChannel $channel): void
    {
        while ($this->running && $channel->isConsuming()) {
            try {
                $channel->wait(0.1);
                Coroutine::sleep(0.01);
            } catch (AMQPTimeoutException $e) {
                Coroutine::sleep(0.01);
            }
        }
    }

    private function handleError(Throwable $e): void
    {
        $this->retryCount++;

        Log::error("RabbitMQ consumer {$this->name} failed with error", [
            'error' => $e->getMessage(),
            'retry' => $this->retryCount,
        ]);

        $waitTime = min(pow(2, $this->retryCount), 60);
        Coroutine::sleep($waitTime);

        if ($this->running && $this->retryCount < self::MAX_RETRIES) {
            Log::info("Retrying RabbitMQ consumer {$this->name} in {$waitTime} seconds");
        }
    }
}
