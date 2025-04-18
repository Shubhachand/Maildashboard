<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        // Register your fetch‐emails command
        \App\Console\Commands\FetchEmails::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Fetch incoming mail every minute
        $schedule->command('mail:fetch')->everyMinute();

        // (You can add other scheduled tasks here…)
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Auto‐load any commands in app/Console/Commands
        $this->load(__DIR__ . '/Commands');

        // You can also define Closure‐based console commands in routes/console.php
        require base_path('routes/console.php');
    }
}