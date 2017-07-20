<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\user;

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
        $users = User::all();

        foreach($users as $user){
            $MaillingController = new \App\Http\Controllers\MaillingController;
            $MaillingController->SendTopupEmail($user['id']);
        }

        return;
    }
}
