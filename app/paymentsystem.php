<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paymentsystem extends Model
{

    protected $table = 'paymentsystems';

    public function coupon()
    {

        return $this->belongsTo('paymentsystem');
    }

    public function paymentlog()
    {

        return $this->belongsTo('paymentlog');
    }
}
