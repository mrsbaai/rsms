<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class generic extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $content;
    protected $subj;

    public function __construct($data)
    {
        $this->content = $data['content'];
        $this->subj = $data['subj'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.generic')
            ->subject($this->subj)
            ->with([
                'content' => $this->content,
            ]);

    }
}
