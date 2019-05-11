<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class flat extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $htm;
    protected $subj;

    public function __construct($data)
    {
        $this->htm = $data['html'];
        $this->subj = $data['subject'];


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        config(['mail.markdown.theme' => 'none']);
        return $this->markdown('emails.flat')
            ->subject($this->subj)
            ->with([
                'html' => $this->htm,

            ]);

    }
}
