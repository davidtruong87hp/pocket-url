<?php

use App\Console\Commands\MaintainShortcodePool;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(MaintainShortcodePool::class)->everyMinute()
    ->withoutOverlapping(10)
    ->onOneServer()
    ->runInBackground();
