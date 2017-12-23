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

    protected $heading1;
    protected $heading2;
    protected $text1;
    protected $text2;
    protected $button;
    protected $buttonURL;
    protected $subj;

    public function __construct($data)
    {
        $this->heading1 = $data['heading1'];
        $this->heading2 = $data['heading2'];
        $this->text1 = $data['text1'];
        $this->text2 = $data['text2'];
        $this->button = $data['button'];
        $this->buttonURL = $data['buttonURL'];
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
                'heading1' => $this->heading1,
                'heading2' => $this->heading2,
                'text1' => $this->text1,
                'text2' => $this->text2,
                'button' => $this->button,
                'buttonURL' => $this->buttonURL
            ]);

    }
}
