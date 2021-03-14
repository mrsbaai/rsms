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
        '\App\Console\Commands\SendPromoMails',
        '\App\Console\Commands\SendAutoMails',
        '\App\Console\Commands\removeExpired',
        '\App\Console\Commands\DemoNumbers'
        //'\App\Console\Commands\SendNumberVerification',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('expired:remove')->daily();
		
		$schedule->command('SendPromoMails:Send')->daily();
        $schedule->command('SendAutoMails:SendMails')->daily();
        $schedule->command('DemoNumbers:Update')->weekly();

        //$schedule->command('Verification:Send')->daily();
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
