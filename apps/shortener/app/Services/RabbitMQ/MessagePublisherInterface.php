<?php

namespace App\Services\RabbitMQ;

interface MessagePublisherInterface
{
    public function publish(string $pattern, array $data): void;
}
