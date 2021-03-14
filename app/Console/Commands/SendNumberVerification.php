<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SMS;
use App\number;

class SendNumberVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Verification:Send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an sms to all free numbers "ex RSMSCODE-EDDSDSDFV"';

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
        $numbers = number::all()->where('is_private',true)->where('email', null);
        $code = "RSMSCODE-" . substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890', ceil(7/strlen($x)) )),1,8);
        $SMS = new SMS();
        foreach ($numbers as $number) {
            $SMS->Send($code,$number['number']);
        }


    }
}
