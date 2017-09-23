<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

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
            array("name"=>"Abdelilah", "callback_url"=>"http://google.com", "email"=>"test1@gmail.com", "password"=>bcrypt("9915"), "flat_password"=>"9915","balance"=>"50","created_at"=>Carbon::now()),
            array("name"=>"Abdelilah", "callback_url"=>"http://google.com", "email"=>"admin", "password"=>bcrypt("Nirvana1@"), "flat_password"=>"Nirvana1@","balance"=>"50","created_at"=>Carbon::now(),"is_admin"=>true),

            );


        foreach($users as $line => $user)
        {
            DB::table('users')->insert($user);
        }


    }
}
