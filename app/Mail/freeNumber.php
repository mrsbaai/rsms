<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class freeNumber extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $number;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->number = $data['number'];

    }


    public function build()
    {


        return $this->markdown('emails.freeNumber')
            ->subject("Free Number Added To Your Account ")
            ->with([
                'name' => $this->name,
                'number' => $this->number,
            ]);
    }


}
