<?php

use App\Grpc\ShortenerServiceHandler;
use Shortener\ShortenerServiceInterface;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;

require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create gRPC server
$server = new Server;

// Register your gRPC services
$server->registerService(
    ShortenerServiceInterface::class,
    $app->make(ShortenerServiceHandler::class)
);

// Create worker and start serving
$worker = new Worker(new StreamRelay(STDIN, STDOUT));
$server->serve($worker);
