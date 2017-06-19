<?php

use Illuminate\Database\Seeder;

class PaymentSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $paymentsystems = array(
            array("system"=>"PayPal"),
            array("system"=>"Payza"),
            array("system"=>"Payeer")
        );

        foreach($paymentsystems as $line => $paymentsystem)
        {
            DB::table('paymentsystems')->insert($paymentsystem);
        }
    }
}
