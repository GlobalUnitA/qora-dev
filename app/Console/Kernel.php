<?php

namespace App\Console;

use \Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('generate:staking-profit')->dailyAt('00:06');
        $schedule->command('reflect:asset-deposit')->dailyAt('00:11');

        //$schedule->command('reflect:asset-deposit')->everyMinute();

        $schedule->command('uploads:clean-tmp')->hourly();

        $schedule->command('crypto:fetch-prices')->everyMinute();
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
