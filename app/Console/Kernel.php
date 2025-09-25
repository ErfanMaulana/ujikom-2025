<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Update motor status dan booking status setiap hari pada jam 00:01
        $schedule->command('motor:update-status')
                 ->dailyAt('00:01')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Backup schedule dapat ditambahkan di sini
        // $schedule->command('backup:run')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}