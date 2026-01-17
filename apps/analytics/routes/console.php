<?php

use App\Console\Commands\AggregateClickStatistics;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(AggregateClickStatistics::class)
    ->dailyAt('01:00')
    ->withoutOverlapping(30)
    ->onOneServer()
    ->runInBackground();
