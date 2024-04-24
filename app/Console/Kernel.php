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
        Commands\CleanTemporaryFiles::class,
        Commands\DpayExpiration::class,
        Commands\PmongoCardExpiration::class,
        Commands\PmongoEWalletExpiration::class,
        Commands\LiveCourse::class,
        Commands\EndCourse::class,
        Commands\VoucherStart::class,
        Commands\VoucherEnd::class,
        Commands\GenerateMonthlyCompletionReport::class,
        Commands\GenerateMonthlyRevenue::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('cleanTempFiles:cron')
        //          ->everyMinute();
        //          $schedule->command('generateMonthlyRevenue:monthlyRevenue')//->monthlyOn(1, '00:00')->runInBackground();
        //          ->everyMinute();
        //          $schedule->command('generateMonthlyCompletionReport:monthlyCompletionReport')//->monthlyOn(1, '00:00')->runInBackground();
        //          ->everyMinute();
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
}
