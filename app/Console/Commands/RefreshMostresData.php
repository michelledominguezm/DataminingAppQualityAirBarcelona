<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RefreshMostresData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mostres:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh air quality data from open data every hour';

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
        echo 'Air quality Status';
        app('App\Http\Controllers\ApiController')->getAirQualityDataCSV(true);

        $this->info('Air quality data refreshed.');
    }
}