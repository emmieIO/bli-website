<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('app:send-event-reminders')->everyMinute();
Schedule::call(function () {
        \Log::info('Cron job ran at ' . now());
})->everyMinute();
