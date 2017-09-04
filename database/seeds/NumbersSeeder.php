<?php

use Illuminate\Database\Seeder;

class NumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {

        $numbers = array(
            array("number"=>"12067932511","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null,"last_checked"=>"2017-01-15 11:38:50"),
            array("number"=>"12067932522","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null,"last_checked"=>"2017-01-20 11:38:50"),
            array("number"=>"12067932533","country"=>"US","expiration"=>"2018-01-29 10:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null,"last_checked"=>"2017-01-25 11:38:50"),
            array("number"=>"12067932544","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null),
            array("number"=>"12067932555","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null),

            array("number"=>"12063266945","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"0","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null),
            array("number"=>"12066695284","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"0","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null),
            array("number"=>"12067652548","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"0","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>null),

            array("number"=>"12067925640","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>"test1@gmail.com"),
            array("number"=>"14123468547","country"=>"US","expiration"=>"2018-01-29 11:38:50","is_private"=>"1","is_active"=>"1","network"=>"voxe","network_login"=>"desew","network_password"=>"maedar.yeti39","email"=>"test1@gmail.com"),

        );


        foreach($numbers as $line => $number)
        {
            DB::table('numbers')->insert($number);
        }


    }
}
