<?php

namespace App\Services\MessageQueue\RabbitMQ;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swoole\Coroutine\Channel;

class RabbitMQConnectionPool
{
    private Channel $pool;

    private array $config;

    private int $size;

    private int $created = 0;

    /**
     * Create a new class instance.
     */
    public function __construct(int $poolSize = 5)
    {
        $this->size = $poolSize;
        $this->pool = new Channel($poolSize);
        $this->config = [
            'host' => config('rabbitmq.host'),
            'port' => config('rabbitmq.port'),
            'user' => config('rabbitmq.user'),
            'password' => config('rabbitmq.password'),
            'vhost' => config('rabbitmq.vhost'),
        ];

        // Pre-create connections
        $this->initialize();
    }

    /**
     * Get a connection from the pool.
     */
    public function get(float $timeout = -1): ?AMQPStreamConnection
    {
        /** @var AMQPStreamConnection */
        $connection = $this->pool->pop($timeout);

        if ($connection && ! $connection->isConnected()) {
            Log::warning('Connection from pool was dead, creating a new one');
            $connection = $this->createConnection();
            $this->created++;
        }

        return $connection ?: null;
    }

    /**
     * Return a connection to the pool.
     */
    public function put(AMQPStreamConnection $connection): void
    {
        if ($connection->isConnected()) {
            $this->pool->push($connection);
        } else {
            Log::warning('Not returning dead connection to pool');
            $this->pool->push($this->createConnection());
        }
    }

    /**
     * Close all connections
     */
    public function close(): void
    {
        while ($this->pool->length() > 0) {
            /** @var AMQPStreamConnection */
            $connection = $this->pool->pop();

            if ($connection && $connection->isConnected()) {
                $connection->close();
            }
        }

        Log::info('RabbitMQ connection pool closed');
    }

    public function getStats(): array
    {
        return [
            'pool_size' => $this->size,
            'total_created' => $this->created,
            'available' => $this->pool->length(),
            'in_use' => $this->size - $this->pool->length(),
        ];
    }

    private function initialize(): void
    {
        for ($i = 0; $i < $this->size; $i++) {
            $connection = $this->createConnection();
            $this->pool->push($connection);
            $this->created++;
        }

        Log::info('RabbitMQ connection pool initialized', [
            'pool_size' => $this->size,
            'connections_created' => $this->created,
        ]);
    }

    private function createConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost'],
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
}
