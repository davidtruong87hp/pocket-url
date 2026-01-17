<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Throwable;

trait InteractsWithRabbitMQ
{
    protected function createQueue(
        string $queue,
        string $exchange,
        string $routingKey,
        bool $withDLQ = true
    ): void {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $channel->exchange_declare($exchange, 'topic', false, true, false);

        $arguments = null;

        if ($withDLQ) {
            $arguments = new AMQPTable;
            $arguments->set('x-dead-letter-exchange', $exchange.'.dlx');
            $arguments->set('x-dead-letter-routing-key', $queue.'.failed');
        }

        $channel->queue_declare($queue, false, true, false, false, false, $arguments);
        $channel->queue_bind($queue, $exchange, $routingKey);

        if ($withDLQ) {
            $dlx = $exchange.'.dlx';
            $dlq = $queue.'.failed';

            $channel->exchange_declare($dlx, 'direct', false, true, false);
            $channel->queue_declare($dlq, false, true, false, false);
            $channel->queue_bind($dlq, $dlx, $dlq);
        }

        $channel->close();
        $connection->close();
    }

    protected function publishMessage(
        string $exchange,
        string $routingKey,
        array $data,
        array $properties = []
    ): void {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $message = new AMQPMessage(
            json_encode($data),
            array_merge([
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ], $properties)
        );

        $channel->basic_publish($message, $exchange, $routingKey);

        $channel->close();
        $connection->close();
    }

    protected function getQueueMessageCount(string $queue): int
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        try {
            [, $messageCount] = $channel->queue_declare($queue, true);
        } catch (Throwable $e) {
            $messageCount = 0;
        } finally {
            $channel->close();
            $connection->close();
        }

        return $messageCount;
    }

    protected function getConsumerCount(string $queue): int
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        try {
            [, $consumerCount] = $channel->queue_declare($queue, true);
        } catch (Throwable $e) {
            $consumerCount = 0;
        } finally {
            $channel->close();
            $connection->close();
        }

        return $consumerCount;
    }

    protected function peekMessages(string $queue, int $count = 1): array
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $messages = [];

        for ($i = 0; $i < $count; $i++) {
            $message = $channel->basic_get($queue);

            if ($message === null) {
                break;
            }

            $messages[] = [
                'body' => json_decode($message->getBody(), true),
                'delivery_tag' => $message->getDeliveryTag(),
            ];

            // Requeue the message
            $message->nack(true);
        }

        $channel->close();
        $connection->close();

        return $messages;
    }

    protected function consumeAllMessages(string $queue): array
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $messages = [];

        while ($message = $channel->basic_get($queue)) {
            $messages[] = json_decode($message->getBody(), true);
            $message->ack();
        }

        $channel->close();
        $connection->close();

        return $messages;
    }

    protected function purgeQueue(string $queue): int
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $purgedCount = $channel->queue_purge($queue);

        $channel->close();
        $connection->close();

        return $purgedCount;
    }

    protected function queueExists(string $queue): bool
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $exists = true;

        try {
            $channel->queue_declare($queue, true);
        } catch (Throwable $e) {
            $exists = false;
        }

        $channel->close();
        $connection->close();

        return $exists;
    }

    protected function exchangeExists(string $exchange, string $type = 'topic'): bool
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        $exists = true;

        try {
            $channel->exchange_declare($exchange, $type, true);
        } catch (Throwable $e) {
            $exists = false;
        }

        $channel->close();
        $connection->close();

        return $exists;
    }

    protected function waitForQueueCount(
        string $queue,
        int $expectedCount,
        int $timeoutSeconds = 5
    ): bool {
        $startTime = time();

        while (time() - $startTime < $timeoutSeconds) {
            if ($this->getQueueMessageCount($queue) === $expectedCount) {
                return true;
            }

            usleep(100000); // 100ms
        }

        return false;
    }

    protected function waitForDLQMessage(
        string $dlqName,
        int $timeoutSeconds = 5
    ): bool {
        return $this->waitForQueueCount($dlqName, 1, $timeoutSeconds);
    }

    protected function waitForDatabaseCount(
        string $table,
        int $expectedCount,
        int $timeoutSeconds = 5
    ): bool {
        $startTime = time();

        while (time() - $startTime < $timeoutSeconds) {
            $actualCount = DB::table($table)->count();

            if ($actualCount >= $expectedCount) {
                return true;
            }

            usleep(200000); // 200ms
        }

        $actualCount = DB::table($table)->count();
        echo "\n⚠️  Timeout: Expected {$expectedCount} records in {$table}, found {$actualCount}\n";

        return false;
    }

    protected function getQueueStats(string $queue): array
    {
        $connection = $this->getRabbitMQConnection();
        $channel = $connection->channel();

        try {
            [, $messageCount, $consumerCount] = $channel->queue_declare($queue, true);

            $stats = [
                'exists' => true,
                'messages' => $messageCount,
                'consumers' => $consumerCount,
            ];
        } catch (Throwable $th) {
            $stats = [
                'exists' => false,
                'messages' => 0,
                'consumers' => 0,
            ];
        }

        $channel->close();
        $connection->close();

        return $stats;
    }

    protected function assertQueueHasMessages(string $queue, int $expectedCount): void
    {
        $actualCount = $this->getQueueMessageCount($queue);

        $this->assertEquals(
            $expectedCount,
            $actualCount,
            "Expected queue $queue to have $expectedCount messages, but found $actualCount"
        );
    }

    protected function assertQueueEmpty(string $queue): void
    {
        $this->assertQueueHasMessages($queue, 0);
    }

    protected function assertMessageInDLQ(string $dlqName, array $expectedData): void
    {
        $messages = $this->peekMessages($dlqName, 1);

        $this->assertNotEmpty($messages, "Expected queue $dlqName to have 1 message, but found none");

        if (! empty($expectedData)) {
            $actualData = $messages[0]['body'];

            foreach ($expectedData as $key => $value) {
                $this->assertEquals(
                    $value,
                    $actualData[$key] ?? null,
                    "Expected message in queue $dlqName to have key '$key' with value '$value', but found '$actualData[$key]' instead"
                );
            }
        }
    }

    private function getRabbitMQConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );
    }
}
