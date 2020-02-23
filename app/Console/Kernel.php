<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $out = $this->scheduleOutput();

        if (app()->environment() === 'production') {
            $schedule->command('image:build')->appendOutputTo($out)->runInBackground()->cron("0 */3 * * *");
            $schedule->command('server:process:started')->appendOutputTo($out)->runInBackground()->everyMinute();
            $schedule->command('server:process:restarted')->appendOutputTo($out)->runInBackground()->everyMinute();
            $schedule->command('server:process:play')->appendOutputTo($out)->runInBackground()->everyMinute();
            $schedule->command('server:process:created')->appendOutputTo($out)->runInBackground()->everyMinute();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    private function scheduleOutput()
    {
        return base_path("storage/logs/schedule-" . date("Y-m-d") . ".log");
    }
}
