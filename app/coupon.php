<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class coupon extends Model
{

    protected $table = 'coupons';

    public function paymentsystem()
    {
        return $this->hasMany('paymentsystem');
    }
}
