<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newCoupon extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $subj;
    protected $header;
    protected $coupon;
    protected $date;

    public function __construct($data)
    {
        $this->subj = $data['subj'];
        $this->header = $data['header'];
        $this->coupon = $data['coupon'];
        $this->date = $data['date'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newCoupon')
            ->subject($this->subj)
            ->with([
                'header' => $this->header,
                'coupon' => $this->coupon,
                'date' => $this->date,
            ]);
    }
}
