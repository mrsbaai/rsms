<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class numbersReady extends Mailable
{
    use Queueable, SerializesModels;
    protected $name;
    protected $numbers;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->numbers = $data['numbers'];

    }


    public function build()
    {

        if  (count($this->numbers) > 1){
            $subject = "<<Receive-SMS>> Your new numbers are all set to go 🚀";
        }else{
            $subject = "<<Receive-SMS>> Your new is ready to use 🚀";
        }


        return $this->markdown('emails.numbersReady')
            ->subject($subject)
            ->with([
                'name' => $this->name,
                'numbers' => $this->numbers,
            ]);
    }
}
