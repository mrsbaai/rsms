<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CouponsSeeder::class);
        $this->call(PaymentSystemSeeder::class);
        $this->call(PaypalSeeder::class);
        $this->call(SendersBlacklistSeeder::class);
        $this->call(StringsBlacklistSeeder::class);
    }
}
