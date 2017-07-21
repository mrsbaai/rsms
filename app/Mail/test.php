<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class test extends Mailable
{
    use Queueable, SerializesModels;
    protected $name;


    public function __construct($data)
    {
        $this->name = $data['name'];

    }


    public function build()
    {
        return $this->markdown('emails.numberRemovalNotification')
            ->subject("number Removal Notification")
            ->with([
                'name' => $this->name,
            ]);
    }
}

