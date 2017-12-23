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
    protected $heading3;
    protected $heading4;
    protected $text3;
    protected $text4;
    protected $button1;
    protected $buttonURL1;
    protected $button2;
    protected $buttonURL2;
    protected $button3;
    protected $buttonURL3;
    protected $img1;
    protected $img2;
    protected $subj;

    public function __construct($data)
    {
        $this->heading1 = $data['heading1'];
        $this->heading2 = $data['heading2'];
        $this->text1 = $data['text1'];
        $this->text2 = $data['text2'];
        $this->heading3 = $data['heading3'];
        $this->heading4 = $data['heading4'];
        $this->text3 = $data['text3'];
        $this->text4 = $data['text4'];
        $this->button1 = $data['button1'];
        $this->buttonURL1 = $data['buttonURL1'];
        $this->button2 = $data['button2'];
        $this->buttonURL2 = $data['buttonURL2'];
        $this->button3 = $data['button3'];
        $this->buttonURL3 = $data['buttonURL3'];
        $this->img1 = $data['img1'];
        $this->img1 = $data['img2'];

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
                'button1' => $this->button1,
                'buttonURL1' => $this->buttonURL1,
                'heading3' => $this->heading3,
                'heading4' => $this->heading4,
                'text3' => $this->text3,
                'text4' => $this->text4,
                'button2' => $this->button2,
                'buttonURL2' => $this->buttonURL2,
                'button3' => $this->button3,
                'buttonURL3' => $this->buttonURL3,
                'img1' => $this->img1,
                'img2' => $this->img2
            ]);

    }
}
