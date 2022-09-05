<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Horizon\Console\HorizonCommand;
use Laravel\Horizon\Events\WorkerProcessRestarting;
use Laravel\Horizon\WorkerProcess;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(HorizonCommand::class)->everyMinute()->withoutOverlapping()->appendOutputTo(storage_path('logs') . '/commandOutput.txt');
        $schedule->command('inspire')->everyMinute()->appendOutputTo(storage_path('logs') . '/commandOutput.txt');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
