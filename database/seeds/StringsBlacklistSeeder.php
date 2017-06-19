<?php

use Illuminate\Database\Seeder;

class StringsBlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strings = array(
            array("string"=>"Lyft.com"),
            array("string"=>"TelAPI"),
            array("string"=>"Nexmo"),
            array("string"=>"Twilio"),
            array("string"=>"receive-sms-online.info"),
            array("string"=>"receivesms"),
            array("string"=>"Invalid command!"),
            array("string"=>"SMS URL to change this message"),
            array("string"=>"For Gametime customer service")

        );


        foreach($strings as $line => $string)
        {
            DB::table('stringsblacklist')->insert($string);
        }

    }
}
