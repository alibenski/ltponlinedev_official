<?php

namespace App\Console;
use Illuminate\Support\Facades\Log;
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
        '\App\Console\Commands\ApprovalReminder',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // insert name and signature of you command and define the time of execution
        // run command to send reminder emails to managers in class \App\Console\Commands\ApprovalReminder
        $schedule->command('ApprovalReminder:approvalreminder')
                ->dailyAt('13:00')
                ->withoutOverlapping();
                
        // run command to execute queued jobs in the jobs table 
        // Log::info("Start queue:work");
        $work = $schedule->command('queue:work --tries=3')
                // ->cron('* * * * * *')
                ->withoutOverlapping();
        // Log::info("End queue:work");
        // there is a 60 second delay to execute job with no changes occured to the code or the query due to queue:restart
        if ($work) {
            // Log::info("Start queue:restart");
            $schedule->command('queue:restart');
            // Log::info("End queue:restart");
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
