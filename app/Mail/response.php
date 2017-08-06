<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class response extends Mailable
{
    use Queueable, SerializesModels;
    protected $subj;
    protected $message;
    protected $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->message = $data['message'];
        $this->subj = $data['subject'];
        $this->name = $data['name'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.response')
            ->from('support@receive-sms.com')
            ->subject($this->subj)
            ->with([
                'message' => $this->message,
                'name' => $this->name,
            ]);

    }
}
