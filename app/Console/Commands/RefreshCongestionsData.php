<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RefreshCongestionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'congestions:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh congestions data from open data every 15 min';

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
        echo 'Congestion Status';
        app('App\Http\Controllers\DataController')->createCongestions(true);

        $this->info('Congestions data refreshed.');
    }
}