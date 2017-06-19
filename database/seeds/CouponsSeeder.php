<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponsSeeder extends Seeder
{


    public function run()
    {


        $expiration = Carbon::now()->addDays(3);
        $coupons= array(
            array("code"=>"WelcomeBack","paymentsystem_id"=>"Payeer","value"=>"60","minimum_price"=>"50","expiration"=>$expiration),
            array("code"=>"WelcomeBack","paymentsystem_id"=>"Payza","value"=>"60","minimum_price"=>"50", "expiration"=>$expiration),
        );


        foreach($coupons as $line => $coupon)
        {
        DB::table('coupons')->insert($coupon);
        }

    }
}
