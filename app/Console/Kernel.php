<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\RefreshMostresData::class,
        Commands\RefreshImmissionsData::class,
        Commands\RefreshDependenciesData::class,
        Commands\RefreshTransitData::class,
        Commands\RefreshResultatsData::class,
        Commands\RefreshCongestionsData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('mostres:refresh')->everyThirtyMinutes()->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/mostres.txt');
        $schedule->command('immissions:refresh')->everyThirtyMinutes()->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/immissions.txt');
        $schedule->command('dependencies:refresh')->weeklyOn(6, '18:00')->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/dependencies.txt');
        $schedule->command('transit:refresh')->everyTenMinutes()->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/transit.txt');
        $schedule->command('congestions:refresh')->cron('0 */2 * * *')->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/congestions.txt');
        $schedule->command('resultats:refresh')->everyThirtyMinutes()->appendOutputTo('/home/vagrant/ProjectApp/DataminingApp/resultats.txt');
    }
}
