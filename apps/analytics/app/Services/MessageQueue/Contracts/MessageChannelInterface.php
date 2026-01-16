<?php

namespace App\Services\MessageQueue;

interface MessageChannelInterface
{
    public function consume(callable $callback): void;

    public function wait(float $timeout = 0.1): void;

    public function isConsuming(): bool;

    public function close(): void;

    public function getQueueName(): string;

    public function getExchangeName(): string;
}
