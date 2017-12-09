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

    protected $subj;
    protected $mess;
    protected $frm;
    protected $name;
    public function __construct($data)
    {
        $this->mess = $data['mess'];
        $this->subj = $data['subj'];
        $this->name = $data['name'];
        $this->frm = $data['frm'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.flat')
        ->from($this->frm,$this->name)
        ->subject($this->subj)
        ->with([
            'message' => $this->mess,
        ]);

    }
}
