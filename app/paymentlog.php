<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paymentlog extends Model
{
    protected $table = 'paymentlog';

    public function paymentsystem()
    {
        return $this->hasOne('paymentsystem');
    }
}
