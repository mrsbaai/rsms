<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class topupNeeded extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->date = $data['date'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.topupNeeded')
            ->subject('Notice - Numbers are pending removal')
            ->with([
                'name' => $this->name,
                'date' => $this->date,
            ])
            ;

    }
}
