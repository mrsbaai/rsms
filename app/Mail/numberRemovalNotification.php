<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class numberRemovalNotification extends Mailable
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
            ->subject("Numbers will be permanently removed withing 72 hours")
            ->with([
                'name' => $this->name,
            ]);
    }
}

