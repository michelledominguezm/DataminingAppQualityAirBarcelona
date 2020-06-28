<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RefreshDependenciesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dependencies:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh contaminants, stations and traffic section data from open data monthly';

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
        /*$request = Request::create('/opendata/air_pollutants', 'GET'); 
        $response = app()->handle($request); 

        $request = Request::create('/opendata/air_stations', 'GET'); 
        $response = app()->handle($request); 

        $request = Request::create('/opendata/sections', 'GET'); 
        $response = app()->handle($request); */
        echo "Date: " . date("Y-m-d h:iA");
        echo 'Pollutants';
        app('App\Http\Controllers\ApiController')->getAirPollutantsData(true);
        echo 'Stations';
        app('App\Http\Controllers\ApiController')->getAirStationsData(true);
        echo 'Traffic sections';
        app('App\Http\Controllers\ApiController')->getSectionsData(true);

        $this->info('Dependencies data refreshed.');
    }
}