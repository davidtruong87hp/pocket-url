<?php

namespace App\Services\MessageQueue\Contracts;

interface MessageConsumerInterface
{
    public function start(): void;

    public function stop(): void;

    public function getName(): string;

    public function isRunning(): bool;
}
