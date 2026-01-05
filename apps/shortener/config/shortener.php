<?php

return [
    'short_domain' => env('SHORT_DOMAIN', 'localhost'),

    'shortcode' => [
        'length' => env('SHORTCODE_LENGTH', 6),
    ],

    'pool' => [
        'target_size' => env('SHORTCODE_POOL_SIZE', 10000),
        'min_size' => env('SHORTCODE_POOL_MIN_SIZE', 1000),
        'refill_batch_size' => env('SHORTCODE_REFILL_BATCH_SIZE', 1000),
    ],

    'rate_limiting' => [
        'default_per_minute' => 10,
        'default_per_hour' => 100,
        'default_per_day' => 1000,
    ],
];
