<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class RabbitMQPublisher implements MessagePublisherInterface
{
    private ?AMQPStreamConnection $connection = null;

    private $channel = null;

    private string $queue;

    public function __construct()
    {
        $this->queue = config('queue.connections.rabbitmq.queue', 'cache.invalidation');
    }

    private function getConnection(): AMQPStreamConnection
    {
        if ($this->connection === null || ! $this->connection->isConnected()) {
            $this->connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host'),
                config('queue.connections.rabbitmq.hosts.0.port'),
                config('queue.connections.rabbitmq.hosts.0.user'),
                config('queue.connections.rabbitmq.hosts.0.password'),
                config('queue.connections.rabbitmq.hosts.0.vhost'),
                false, // insist
                'AMQPLAIN', // login method
                null, // login response
                'en_US', // locale
                3.0, // connection timeout
                3.0, // read write timeout
                null, // context
                true, // keepalive
                60 // heartbeat
            );
        }

        return $this->connection;
    }

    private function getChannel()
    {
        if ($this->channel === null) {
            $this->channel = $this->getConnection()->channel();

            $this->channel->queue_declare(
                $this->queue,
                false, // passive
                true, // durable
                false, // exclusive
                false // auto delete
            );
        }

        return $this->channel;
    }

    public function publish(string $pattern, array $data): void
    {
        try {
            $payload = json_encode([
                'pattern' => $pattern,
                'data' => $data,
            ]);

            $message = new AMQPMessage(
                $payload, [
                    'content_type' => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                    'timestamp' => time(),
                ]
            );

            $this->getChannel()->basic_publish($message, '', $this->queue);

            logger()->info('Published to RabbitMQ', [
                'pattern' => $pattern,
                'queue' => $this->queue,
            ]);
        } catch (Throwable $exception) {
            logger()->error('Failed to publish to RabbitMQ', [
                'pattern' => $pattern,
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            $this->closeConnection();

            throw $exception;
        }
    }

    public function closeConnection(): void
    {
        try {
            if ($this->channel !== null) {
                $this->channel->clone();
                $this->channel = null;
            }

            if ($this->connection !== null && $this->connection->isConnected()) {
                $this->connection->close();
                $this->connection = null;
            }
        } catch (Throwable $th) {
            logger()->warning('Failed to close RabbitMQ connection', [
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function __destruct()
    {
        $this->closeConnection();
    }
}
