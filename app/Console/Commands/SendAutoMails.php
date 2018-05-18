<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\user;
use App\subscriber;

class SendAutoMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendAutoMails:SendMails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic marketing emails to users';

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
        $users = User::all()->where('confirmed' , true)->where('is_active' , true);
        $subscribers = subscriber::all()->where('confirmed' , true);
        $sendedEmails = array();

        foreach($users as $user){
            if (! in_array($user["email"], $sendedEmails)){
                $MaillingController = new \App\Http\Controllers\MaillingController;
                $MaillingController->SendAutoPromoEmail($user['id'],true);
                array_push($sendedEmails, $user["email"]);
            }

        }

        foreach($subscribers as $subscriber){
            if (! in_array($subscriber["email"], $sendedEmails)){
                $MaillingController = new \App\Http\Controllers\MaillingController;
                $MaillingController->SendAutoPromoEmail($subscriber['id'],false);
                array_push($sendedEmails, $subscriber["email"]);
            }

        }

        print_r($sendedEmails);
        return;
    }
}
