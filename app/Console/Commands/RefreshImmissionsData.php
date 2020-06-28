<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RefreshImmissionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'immissions:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh air immissions data from open data every hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Date: " . date("Y-m-d h:iA");
        echo 'Immissions Status';
        app('App\Http\Controllers\ApiController')->getAirPollutionData(true);

        $this->info('Air quality data refreshed.');
    }
}