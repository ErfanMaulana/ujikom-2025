<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule motor status updates
Schedule::command('motor:update-status')
    ->dailyAt('00:01') // Run every day at 00:01
    ->name('update-motor-status')
    ->description('Update motor status based on active bookings');

// Schedule motor status updates every hour during business hours
Schedule::command('motor:update-status')
    ->hourly()
    ->between('8:00', '22:00')
    ->name('update-motor-status-hourly')
    ->description('Hourly motor status updates during business hours');
