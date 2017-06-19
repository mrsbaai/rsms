<?php

use Illuminate\Database\Seeder;


class SendersBlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $senders = array(
            array("number"=>"17862086586"),
        );


        foreach($senders as $line => $sender)
        {
            DB::table('sendersblacklist')->insert($sender);
        }

    }
}
