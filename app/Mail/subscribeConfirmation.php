<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class subscribeConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        //
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscribeConfirmation')
            ->subject('Receive-SMS loves you because you\'re awesome!')
            ->with([
                'email' => $this->email,
            ]);
    }
}
