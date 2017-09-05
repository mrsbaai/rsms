<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponsSeeder extends Seeder
{


    public function run()
    {


        $expiration = Carbon::now()->addDays(30);
        $coupons= array(
            array("code"=>"WelcomeBack","paymentsystem_id"=>"Payeer","value"=>"25","minimum_price"=>"50","expiration"=>$expiration),
            array("code"=>"WelcomeBack","paymentsystem_id"=>"Paypal","value"=>"25","minimum_price"=>"50", "expiration"=>$expiration),
            array("code"=>"WelcomeBack","paymentsystem_id"=>"Payza","value"=>"25","minimum_price"=>"50", "expiration"=>$expiration),
        );


        foreach($coupons as $line => $coupon)
        {
        DB::table('coupons')->insert($coupon);
        }

    }
}
