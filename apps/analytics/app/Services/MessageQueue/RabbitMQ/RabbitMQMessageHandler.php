<?php

namespace App\Services\MessageQueue\RabbitMQ;

use App\Services\MessageQueue\Contracts\MessageHandlerInterface;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class RabbitMQMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private string $handlerClass
    ) {}

    public function handle(AMQPMessage $message): void
    {
        $data = $this->parseMessage($message);

        $this->dispatch($data);
        $message->ack();
    }

    public function handleFailure(AMQPMessage $message, Throwable $exception): void
    {
        Log::error('Message processing failed', [
            'handler' => $this->handlerClass,
            'error' => $exception->getMessage(),
        ]);

        $message->nack(true); // Requeue
    }

    private function parseMessage(AMQPMessage $message): array
    {
        $data = json_decode($message->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON: '.json_last_error_msg());
        }

        return $data;
    }

    private function dispatch(array $data): void
    {
        if (class_exists($this->handlerClass)) {
            $this->handlerClass::dispatchSync($data);
        } elseif (is_callable($this->handlerClass)) {
            call_user_func($this->handlerClass, $data);
        } else {
            throw new \RuntimeException("Invalid handler class: {$this->handlerClass}");
        }
    }
}
