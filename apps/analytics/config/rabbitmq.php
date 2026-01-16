<?php

return [
    'host' => env('RABBITMQ_HOST', 'localhost'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'vhost' => env('RABBITMQ_VHOST', '/'),

    /*
    |--------------------------------------------------------------------------
    | Swoole Consumer Configuration
    |--------------------------------------------------------------------------
    | Define multiple consumers that will run concurrently in coroutines
    */
    'workers' => env('RABBITMQ_WORKERS', 10),

    'connection_pool_size' => env('RABBITMQ_CONNECTION_POOL_SIZE', 3),

    'consumers' => [
        /*
        |----------------------------------------------------------------------
        | Click Tracking Consumer
        |----------------------------------------------------------------------
        | Consumes click events from the redirector service
        */
        [
            'name' => 'click_tracker',
            'exchange' => env('RABBITMQ_EXCHANGE', 'analytics.exchange'),
            'exchange_type' => 'topic',
            'queue' => env('RABBITMQ_QUEUE', 'analytics_clicks'),
            'routing_key' => 'click.tracked',
            'handler' => \App\Jobs\ProcessClickEvent::class,
            'instances' => env('RABBITMQ_CLICK_CONSUMERS', 1),
            'prefetch_count' => 10,
            'durable' => true,
        ],
    ],
];
