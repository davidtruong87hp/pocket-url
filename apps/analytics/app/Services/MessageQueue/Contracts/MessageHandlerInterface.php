<?php

namespace App\Services\MessageQueue\Contracts;

use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

interface MessageHandlerInterface
{
    public function handle(AMQPMessage $message): void;

    public function handleFailure(AMQPMessage $message, Throwable $exception): void;
}
