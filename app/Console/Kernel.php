<?php

namespace App\Console;

use App\Jobs\Mails\Logistica\Pendientes as LogiticaPendientes;
use App\Jobs\Mails\Proveedores\AcusesPendientes;
use App\Jobs\Proveedores\Bloquear;
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
        $schedule->job(new Bloquear())->dailyAt('23:59');
        $schedule->job(new AcusesPendientes())->cron('0 */72 * * *');
        $schedule->job(new LogiticaPendientes())->weeklyOn(7, '23:00');
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
