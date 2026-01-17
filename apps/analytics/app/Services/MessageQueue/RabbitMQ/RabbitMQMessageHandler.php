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

    public function handleFailure(AMQPMessage $message, Throwable $e): void
    {
        Log::error('Message processing failed', [
            'handler' => $this->handlerClass,
            'error' => $e->getMessage(),
            'body' => $message->getBody(),
        ]);

        // Check if it's a permanent failure (bad data)
        if ($e instanceof \RuntimeException ||
            $e instanceof \InvalidArgumentException ||
            str_contains($e->getMessage(), 'Undefined array key')) {
            Log::warning('Permanent failure, sending to DLQ', [
                'error' => $e->getMessage(),
            ]);

            $message->nack(false); // Send to DLQ
        } else {
            Log::warning('Temporary failure, requeuing', [
                'error' => $e->getMessage(),
            ]);

            $message->nack(true); // Requeue
        }
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
