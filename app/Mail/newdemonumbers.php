<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newdemonumbers extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $numbers;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $numbers)
    {
        $this->email = $email;
        $this->numbers = $numbers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newdemonumbers')
            ->subject('Fresh Demo Numbers Live!')
            ->with([
                'numbers' => $this->numbers,
                'email' => $this->email,
            ]);
    }
}
