<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class numberRemovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $name;
    protected $numbers;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->numbers = $data['numbers'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if  (count($this->numbers) > 1){
            $subject = "Numbers will be removed withing 72 Hours";
        }else{
            $subject = "Your numbers will be removed withing 72 Hours";
        }

        return $this->markdown('emails.numberRemovalNotification')
            ->subject($subject)
            ->with([
                'name' => $this->name,
                'numbers' => $this->numbers,
            ]);
    }
}
