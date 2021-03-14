<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateDemoNumbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update demo numbers and send notification to all subscribers';

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
        echo 'test';
        return;
    }
}
