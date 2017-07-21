<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class topupReceipt extends Mailable
{
    use Queueable, SerializesModels;
    protected $name;
    protected $date;
    protected $amount;
    protected $finalBalance;
    protected $type;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->date = $data['date'];
        $this->amount = $data['amount'];
        $this->finalBalance = $data['finalBalance'];
        $this->type = $data['type'];
    }


    public function build()
    {
        return $this->markdown('emails.topupReceipt')
            ->subject("<<Receive-SMS>> Here’s your receipt 💸")
            ->with([
                'name' => $this->name,
                'date' => $this->date,
                'amount' => $this->amount,
                'finalBalance' => $this->finalBalance,
                'type' => $this->type,
            ]);
    }
}
