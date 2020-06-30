<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\number;
use App\user;
use App\message;
use Carbon\Carbon;

use Log;

use Mail;

use App\Mail\topupNeeded;

use App\Http\Controllers\PaymentController;

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
        $sendedEmails = array();
        foreach($numbers as $number){
            $date = Carbon::parse($number['expiration']);
            $diff = $now->diffInDays($date, false);
            if ($diff < 0){
                // auto renew
                $email = $number['email'];
                $user = User::whereemail($email)->first();

                $PaymentController = new PaymentController();
                $price = $PaymentController->getPrice(1,1,$email);

                if ($price <= $user['balance']){
                    // renew number
                    $expiration = Carbon::now()->addMonths(1);
                    $balance = $user['balance'] - $price;

                    User::where('email', '=', $email)->update(['balance' => $balance]);
                    Number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
					echo "number renewed: " . $number['number'];
                    $n = $number['number'];



                }else{
                    // remove number
                    $expiration = Carbon::now()->addYears(20);
                    Number::where('id', '=', $number['id'])->update(['email' => null]);
                    Number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
					echo "number removed: " . $number['number'];
					
                    // remove messages
                    Message::where('receiver', '=', $number['number'])->delete();

                    //
                    $n = $number['number'];
                }

            }

            if ($diff == 1){
                // send TOP UP needed
                if (! in_array( $number['email'], $sendedEmails)){
                    $count = $count + 2;
                    $when = Carbon::now()->addMinutes($count);

                    $user = User::whereemail($number['email'])->first();

                    $data['name'] = $user['name'];
                    $data['date'] = Carbon::parse($number['expiration'])->toDateString();
					echo "topup needed email: " . $number['number'];
                    Mail::to($number["email"])->later($when, new topupNeeded($data));
					
                    array_push($sendedEmails, $number['email']);

                    $m = $number['email'];
                    $f = $data['date'];
                }


            }
        }



        // check and update demo numbers

        
        $count_free = number::where('network', 'textnow')->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('last_checked', '>', Carbon::now()->subDays(3)->toDateTimeString())->count();
        if ($count_free > 3){
            $message = message::where('is_private',false)->orderBy('date', 'desc')->first();

            if ($message['date'] < Carbon::now()->subMinutes(120)){
                number::where('is_private', false)->update(['is_private' => true]);
        
                $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->take(3);
                $expiration = Carbon::now()->addMonth(20)->addDays(10);
    
                foreach ($numbers as $number) {
                    $number = number::where('id', '=', $number['id'])->first();
                    number::where('id', '=', $number['id'])->update(['is_private' => false]);
                    number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
                    message::where('receiver', $number['number'])->delete();
                }
    
                $Simplepush = new Simplepush;
                $Simplepush->send("W6T4J9", "Demo numbers updated", "Demo numbers updated", "Demo numbers updated");
        
        
            }

        }     

        return;
    }
}