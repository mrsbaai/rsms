<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class confirmEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $confirmation_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($confirmation_code)
    {
        //
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.confirmEmail')
            ->subject('Verify your email address')
            ->with([
                'confirmation_code' => $this->confirmation_code,
            ]);
    }
}
