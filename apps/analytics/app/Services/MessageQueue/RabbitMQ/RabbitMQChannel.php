<?php

namespace App\Services\MessageQueue\RabbitMQ;

use App\Services\MessageQueue\MessageChannelInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQChannel implements MessageChannelInterface
{
    private AMQPChannel $channel;

    public function __construct(
        private AMQPStreamConnection $connection,
        private array $config
    ) {
        $this->channel = $connection->channel();
        $this->setup();
    }

    /**
     * Start consuming messages
     */
    public function consume(callable $callback): void
    {
        $this->channel->basic_consume(
            $this->config['queue'],
            '',
            false,
            false,
            false,
            false,
            $callback
        );
    }

    /**
     * Wait for messages
     */
    public function wait(float $timeout = 0.1): void
    {
        $this->channel->wait(null, true, $timeout);
    }

    public function isConsuming(): bool
    {
        return $this->channel->is_consuming();
    }

    public function close(): void
    {
        $this->channel->close();
    }

    public function getQueueName(): string
    {
        return $this->config['queue'];
    }

    public function getExchangeName(): string
    {
        return $this->config['exchange'];
    }

    private function setup(): void
    {
        $this->declareExchange();
        $this->declareQueue();
        $this->bindQueue();
        $this->setQoS();
    }

    private function declareExchange(): void
    {
        $this->channel->exchange_declare(
            $this->config['exchange'],
            $this->config['exchange_type'] ?? 'topic',
            false,
            $this->config['durable'] ?? true,
            false
        );
    }

    private function declareQueue(): void
    {
        $this->channel->queue_declare(
            $this->config['queue'],
            false,
            $this->config['durable'] ?? true,
            false,
            false
        );
    }

    private function bindQueue(): void
    {
        if (! isset($this->config['routing_key'])) {
            return;
        }

        $routingKeys = is_array($this->config['routing_key'])
            ? $this->config['routing_key']
            : [$this->config['routing_key']];

        foreach ($routingKeys as $routingKey) {
            $this->channel->queue_bind(
                $this->config['queue'],
                $this->config['exchange'],
                $routingKey
            );
        }
    }

    private function setQoS(): void
    {
        $prefetchCount = $this->config['prefetch_count'] ?? 10;
        $this->channel->basic_qos(0, $prefetchCount, false);
    }
}
