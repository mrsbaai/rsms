<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class SendNumberVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateDemo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update demo numbers and send an email to subscribers';

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
        echo "im inside";

    }
}
