<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use carbon\carbon;
use App\pendinglist;

class SendPromoMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendPromoMails:Send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Pending List';

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
        $pendinglist = pendinglist::all();
        foreach($pendinglist as $entry){
            if(carbon::now()->gte(carbon::parse($entry['sendingdate']))){

                Mail::to($entry['email'])->queue(new generic($entry));
                echo $entry['subject'] . " -> " . $entry['email'] . "<br>   ";
                $entry->delete();
            }

        }

    }
}
