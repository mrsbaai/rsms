<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $logs = array(
            array("payedAmount"=>"50","originalAmount"=>"50","code"=>"","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0","source"=>"http://google.com","created_at"=>Carbon::now()),
            array("payedAmount"=>"50","originalAmount"=>"50","code"=>"xxxxxxx","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0", "source"=>"http://google.com","created_at"=>Carbon::now()),
            array("payedAmount"=>"100","originalAmount"=>"50","code"=>"2ssdsqfsdf","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0", "source"=>"http://google.com", "created_at"=>Carbon::yesterday()),
            array("payedAmount"=>"100","originalAmount"=>"50","code"=>"2ssdsqfsdf","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0", "source"=>"http://yahoo.com", "created_at"=>Carbon::yesterday()),
            array("payedAmount"=>"100","originalAmount"=>"50","code"=>"2ssdsqfsdf","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0", "source"=>"http://yahoo.com", "created_at"=>Carbon::yesterday()),
            array("payedAmount"=>"50","originalAmount"=>"50","code"=>"2ssdsqfsdf","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0", "source"=>"http://google.com","created_at"=>Carbon::tomorrow()),
            array("payedAmount"=>"100","originalAmount"=>"50","code"=>"2ssdsqfsdf","status"=>"Completed","type"=>"received","userEmail"=>"test1@gmail.com","buyerEmail"=>"test1@gmail.com","accountId"=>"4P8SNGQ26DA4L","paymentSystemId"=>"0","created_at"=>Carbon::tomorrow()),

            );


        foreach($logs as $line => $log)
        {
            DB::table('paymentlog')->insert($log);
        }
    }
}
