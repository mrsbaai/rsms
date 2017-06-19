<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaypalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 'created_at' => Carbon::now(),
     * @return void
     */

    public function run()
    {
        $accounts = array(
            array("paypalid"=>"97WCVTLRLVT9J","created_at"=>Carbon::now()),
        );


        foreach($accounts as $line => $account)
        {
            DB::table('paypalids')->insert($account);
        }

    }
}
