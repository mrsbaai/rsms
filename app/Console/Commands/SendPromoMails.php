<?php

namespace App\Console\Commands;

use App\flatpendinglist;
use App\Mail\flat;
use Illuminate\Console\Command;
use carbon\carbon;
use App\pendinglist;
use App\Mail\generic;
use Mail;

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

                Mail::to($entry['email'])
                
                ->queue(new generic($entry));
                $this->info($entry['subject'] . " -> " . $entry['email']  . "\n");
                $entry->delete();
            }

        }

        $flatpendinglist = flatpendinglist::all();
        foreach($flatpendinglist as $entry){
            if(carbon::now()->gte(carbon::parse($entry['sendingdate']))){

                $mailable = new flat($entry);

                if ($entry['from_email'] != "" and $entry['from_email'] != null and $entry['from_name'] != "" and $entry['from_name'] != null){
                    $mailable->replyTo($entry['from_email'], $entry['from_name']);
                    $mailable->from($entry['from_email'], $entry['from_name']);
                }


                Mail::to($entry['email'])
                                       ->queue($mailable);

                $this->info($entry['subject'] . " -> " . $entry['email']  . "\n");
                $entry->delete();
            }

        }

    }
}
