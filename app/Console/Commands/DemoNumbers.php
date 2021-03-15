<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\number;
use App\subscriber;
use App\message;
use Carbon\Carbon;
use App\suppression;
use App\Mail\newdemonumbers;

use Log;

class DemoNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateDemoNumbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update demo numbers and send notification to all subscribers';

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
        
        echo "<html>inside 0 <br/>";
        $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
        $demoNumbers = number::all()->where('is_private',false)->where('is_active',true)->sortBydesc('last_checked');
        echo "inside 1 <br/>";
        foreach ($demoNumbers as $demoNumber) {

            echo "inside 2 <br/>";

                $count_free = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->count();
                echo "inside 3 <br/>";
            if ($count_free > 1){
                echo "inside 4 <br/>";
                    $newNumber = number::where('info', null)->where('network_login', 'not like', 'aa@%')->where('email', null)->where('is_private', true)->where('is_active', true)->where('last_checked', '>', Carbon::now()->subDays(5)->toDateTimeString())->first();
                    $count_free = $count_free -1;
            
                    $expiration = Carbon::now()->addMonth(20)->addDays(10);  
                        //number::where('id', '=', $newNumber['id'])->update(['is_private' => false]);
                        //number::where('id', '=', $newNumber['id'])->update(['expiration' => $expiration]);
                        //message::where('receiver', $newNumber['number'])->delete();
                        //number::where('id', $demoNumber['id'])->update(['is_active' => false]);
                        //number::where('id', $demoNumber['id'])->update(['is_private' => true]);

                    $numbers = number::all()->where('is_private',false)->where('is_active',true);
                    $data['numbers'] = array();
                    foreach ($numbers as $number) {
                        echo "inside 5 <br/>";
                        $number = number::where('id', '=', $number['id'])->first();
                        $addedNumber = array($number['number'],$number['country'],"International");          
                        array_push($data['numbers'],$addedNumber);
                    }
                    echo "inside 6 <br/>";
                   
                    print_r( $data['numbers']);



                    $plucked = suppression::all()->pluck('email');
                    $suppression =  $plucked->all();
                    $list = subscriber::all()->where("confirmed","=",true)->pluck('email');

                    print_r($list);
                    return;
                    $list = array_diff($list, $suppression);

                    $emails = array();
                    foreach($list as $email){
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $filterd_emails[] = $email;
                        }
                    }
            
                    //test
                    $data['email'] = "abdelilah.sbaai@gmail.com";
                    Mail::to($data['email'])->queue(new newdemonumbers($data));
                        
                                            
                    foreach($emails as $email) {
                        $data['email'] = $email;
                        //send an email to subscriber
                        //Mail::to($email)->queue(new newdemonumbers($data));
                        

                    }
                    
                   
                          
                        
                }
            
        }

    }
}
