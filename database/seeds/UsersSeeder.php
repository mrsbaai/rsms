<?php

use Illuminate\Database\Seeder;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $users = array(
            array("name"=>"Abdelilah", "callback_url"=>"http://google.com", "email"=>"test1@gmail.com", "password"=>bcrypt("9915"), "flat_password"=>"9915","balance"=>"50"),
            array("name"=>"Abdelilah", "callback_url"=>"http://google.com", "email"=>"abdelilahsbaai@gmail.com", "password"=>bcrypt("9915"), "flat_password"=>"9915","balance"=>"50"),

            );


        foreach($users as $line => $user)
        {
            DB::table('users')->insert($user);
        }


    }
}
