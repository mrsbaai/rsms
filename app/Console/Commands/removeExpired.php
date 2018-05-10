<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\number;
use App\user;

use Carbon\Carbon;

use Log;
class removeExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check all numbers and remove user if number is expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        $numbers = Number::where('email','!=','null')->get();

        $count = 0;
        foreach($numbers as $number){
            $date = Carbon::parse($number['expiration']);
            $diff = $now->diffInDays($date, false);
            if ($diff <= 0){
                // auto renew
                $email = $number['email'];
                $user = User::whereemail($email)->first();

                $PaymentController = new PaymentController();
                $price = $PaymentController->getPrice(1,1,$email);

                if ($price <= $user['balance']){
                    // renew number
                    $expiration = Carbon::now()->addMonths(1);
                    $balance = $user['balance'] - $price;

                    //User::where('email', '=', $email)->update(['balance' => $balance]);
                    //Number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);

                    $n = $number['number'];
                    Log::info("renew number: $email -> $balance - $n -> $expiration");



                }else{
                    // remove number
                    $expiration = Carbon::now()->addYears(20);
                    //Number::where('id', '=', $number['id'])->update(['email' => null]);
                    //Number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);

                    $n = $number['number'];
                    Log::info("remove number: $n");
                }

            }

            if ($diff > 0 and $diff < 4){
                // send TOP UP needed
                $count = $count + 2;
                $when = Carbon::now()->addMinutes($count);

                $user = User::whereemail($email)->first();
                
                $data['name'] = $user['name'];
                $data['date'] = $number['expiration'];

                //Mail::to($number["email"])->later($when, new topupNeeded($data));

                Mail::to("abdelilah.sbaai@gmail.com")->later($when, new topupNeeded($data));

                $m = $number["email"];
                Log::info("send TOP UP needed: $m");

            }
        }

        return;
    }
}
