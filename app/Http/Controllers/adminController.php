<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\user;
use App\suppression;
use App\number;
use App\message;
use App\subscriber;
use App\contact;
use App\paymentlog;
use App\coupon;
use Illuminate\Support\Facades\Input;
use carbon\carbon;
use Charts;

use Mail;
use App\Mail\numbersReady;
use App\Mail\response;
use App\mail\generic;


use Illuminate\Mail\Markdown;

class adminController extends Controller
{

    Public function dashboard(){


        if ($this->isAdmin()){
            return view('admin.dashboard');
        }else{
            if (Auth::check()){
                return response()->json(['error' => 'Not authorized.'],403);
            }else{
                return view('auth.login');
            }

        }
    }


    public function incomeChart(){
        $chart = Charts::database(paymentlog::all()->where('status',"Completed"), 'line', 'highcharts')
            ->title("Income Chart")
            ->elementLabel("Total")
            ->aggregateColumn('payedAmount','sum')
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }


    public function subscribersChart(){
        $chart = Charts::database(Subscriber::all(), 'line', 'highcharts')
            ->title("Subscribers Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function unsubscribersChart(){
        $chart = Charts::database(suppression::all(), 'line', 'highcharts')
            ->title("Unsubscribers Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function topupsChart(){
        $chart = Charts::database(paymentlog::all()->where('status',"Completed"), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function couponChart(){
        $chart = Charts::database(paymentlog::all()->where('code',"<>",''), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function chargebacksChart(){
        $chart = Charts::database(paymentlog::all()->where('paymentSystemId',"PayPal")->where('type',"new_case"), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    public function registrationChart(){
        $chart = Charts::database(User::all(), 'line', 'highcharts')
            ->title("Top-Ups Chart")
            ->elementLabel("Total")
            ->dimensions(0, 400)
            ->groupByDay();
        return view('admin.chart')->with('chart',$chart);

    }

    Public function isAdmin(){
        if (Auth::check()){
            if (Auth::user()->is_admin){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }



    Public function formatData($records, $columns){

        $rows = [];
        foreach($records as $index => $record) {
            $row = [];
            foreach($columns as $column){
                $newRow = $record[$column];
                if($column == "created_at"){
                    $newRow = $this->nicetime($record[$column]);
                }
                array_push($row, $newRow);
            }
            array_push($rows, $row);
        }

       return array('rows' => $rows, 'columns' => $columns);
    }



    public function showNumbers(){
        $records = number::all()->where('is_removed',false)->sortByDesc('id');
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email", "is_active");
        $data = $this->formatData($records,$columns);
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    public function showTopups(){
        $records = paymentlog::all()->where('status',"Completed")->sortByDesc('id');
        $columns =  array("id", "created_at", "payedAmount", "originalAmount", "code", "userEmail", "buyerEmail", "paymentSystemId");
        $data = $this->formatData($records,$columns);
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }


    public function showSources(){

        if (Input::get('type')){
            $type = Input::get('type');
            $period = Input::get('period');
        }else{
            return view('admin.sources');

        }

        switch ($period){
            case "24h":
                $startDate = Carbon::now()->subDay();
                break;
            case "7 Days":
                $startDate = Carbon::now()->subDays(7);
                break;
            case "1 Month":
                $startDate = Carbon::now()->subMonth();
                break;
            case "3 Months":
                $startDate = Carbon::now()->subMonths(3);
                break;
            case "All":
                $startDate = Carbon::now()->subYears(20);
                break;
        }


        switch ($type){
            case "Topups":
                $chart = Charts::database(paymentlog::all()->where('status',"Completed")->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Topup Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');

                break;
            case "Subscribes":
                $chart = Charts::database(subscriber::all()->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Subscribe Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');
                break;
            case "Registrations":
                $chart = Charts::database(user::all()->where('created_at', '>', $startDate)->sortByDesc('id'), 'pie', 'highcharts')
                    ->title("Registration Sources: " . $period)
                    ->elementLabel("Total")
                    ->aggregateColumn('source','sum')
                    ->dimensions(0, 400)
                    ->groupBy('source');
                break;
            case "Renews":
                break;
            case "Spending":
                break;
        }



        return view('admin.chart')->with('chart',$chart);
    }




    public function showOrders(){
        $records = number::all();
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email");
        $data = $this->formatData($records,$columns);
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    Public function support(){


        $records = contact::all()->where('is_responded',false)->sortByDesc('id');
        $columns =  array("id", "is_support", "created_at", "subject", "name", "email","message");

        $data = $this->formatData($records,$columns);
        return view('admin.support')->with('rows', $data['rows'])->with('columns', $data['columns']);

    }


    public function mailer(){
        return view("admin.mailer");
    }

    public function preview($text1, $text2, $text3, $text4, $heading1, $heading2, $heading3, $heading4, $img1, $img2, $button1, $button2, $button3,$buttonURL1, $buttonURL2, $buttonURL3){

        $heading1 =  base64_decode($heading1);
        $heading2 =  base64_decode($heading2);
        $heading3 =  base64_decode($heading3);
        $heading4 =  base64_decode($heading4);

        $text2 =  base64_decode($text2);
        $text1 =  base64_decode($text1);
        $text3 =  base64_decode($text3);
        $text4 =  base64_decode($text4);
        $button1 =  base64_decode($button1);
        $buttonURL1 =  base64_decode($buttonURL1);
        $button2 =  base64_decode($button2);
        $buttonURL2 =  base64_decode($buttonURL2);
        $button3 =  base64_decode($button3);
        $buttonURL3 =  base64_decode($buttonURL3);
        $img1 =  base64_decode($img1);
        $img2 =  base64_decode($img2);

        if ($heading1 == "nothing"){$heading1 = null;}
        if ($heading2 == "nothing"){$heading2 = null;}
        if ($text2 == "nothing"){$text2 = null;}
        if ($text1 == "nothing"){$text1 = null;}

        if ($heading3 == "nothing"){$heading3 = null;}
        if ($heading4 == "nothing"){$heading4 = null;}
        if ($text3 == "nothing"){$text3 = null;}
        if ($text4 == "nothing"){$text4 = null;}


        if ($button1 == "nothing"){$button1 = null;}
        if ($buttonURL1 == "nothing"){$buttonURL1 = null;}
        if ($button2 == "nothing"){$button2 = null;}
        if ($buttonURL2 == "nothing"){$buttonURL2 = null;}
        if ($button3 == "nothing"){$button3 = null;}
        if ($buttonURL3 == "nothing"){$buttonURL3 = null;}

        if ($img1 == "nothing"){$img1 = null;}
        if ($img2 == "nothing"){$img2 = null;}

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.generic',
            [
                'img1' => $img1,
                'img2' => $img2,
                'button1' => $button1,
                'button2' => $button2,
                'button3' => $button3,
                'text1' => $text1,
                'heading1' => $heading1,
                'heading2' => $heading2,
                'text3' => $text3,
                'text4' => $text4,
                'heading3' => $heading3,
                'heading4' => $heading4,
                'text2' => $text2,
                'buttonURL1' => $buttonURL1,
                'buttonURL2' => $buttonURL2,
                'buttonURL3' => $buttonURL3
            ]

        );
    }

    public function coupon(){

        $records = coupon::all()->where('is_active',"=",true)->where('expiration', '>', Carbon::now());
        $columns =  array("id", "code", "minimum_price", "paymentsystem_id", "value", "expiration");

        $data = $this->formatData($records, $columns);
        return View('admin.coupon')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }


    public function give(){
        return view('admin.give');
    }

    public function addCoupon(){

        $coupon = new coupon();

        $coupon->code = Input::get('code');
        $coupon->minimum_price = Input::get('minimum_price');
        $coupon->paymentsystem_id = Input::get('paymentsystem_id');
        $coupon->value = Input::get('value');
        $coupon->expiration = carbon::parse(Input::get('expiration'));
        $coupon->save();

        flash()->overlay('Coupon Saved ;)', 'Nice!');

        return $this->coupon();

    }

    public function test(){
        $user = User::where('email','test1@gmail.com')->first();
        $source = $user['source'];
    }

    public function giveNumbers(){

        $amount = Input::get('amount');
        $email = Input::get('user_email');

        $user = user::all()->where('email','=',$email)->first();
        $name = $user->name;
        $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->take($amount);
        $expiration = Carbon::now()->addMonth(1)->addDays(10);

        $data['numbers'] = array();
        foreach ($numbers as $number) {
            $number = number::where('id', '=', $number['id'])->first();
            number::where('id', '=', $number['id'])->update(['email' => $email]);
            number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
            $addedNumber = array($number['number'],$number['country'],"International",$expiration);
            array_push($data['numbers'],$addedNumber);
        }



        $data['name'] = $name;
        Mail::to($email)->queue(new numbersReady($data));

        flash()->overlay("You successfully added $amount numbers to " . $name .  "'s account! (" . $email . ").", 'Good');

        return $this->give();

    }

    public function dataFix (){

        $users = user::all()->where('password', null);

        foreach ($users as $user) {

            if ($user['flat_password'] == null or $user['flat_password'] == "" or $user['flat_password'] == "1"){
                $user->update(['flat_password' => "ef5f5zz5x"]);
            }

            if ($user['password'] == null or $user['password'] == ""){

                $password = bcrypt($user['flat_password']);
                $user->update(['password' => $password]);
            }

            if ($user['name'] == null or $user['name'] == "" or $user['name'] == "n"){
                $split = explode("@", $user['email']);

                $name = $split[0];
                $user->update(['name' => $name]);

            }




        }


    }

    public function deleteEmail($id){
        $record = contact::where('id',$id)->get()->first();
        $record->delete();
        return $this->support();

    }
    public function sendResponse(){


        $id = Input::get('id');
        $email = Input::get('email');
        $data['subject'] = "Re: " . Input::get('subject');
        $data['message']= Input::get('response');
        $data['name']= Input::get('name');

        Mail::to($email)->queue(new response($data));

        $record = contact::all()->where('id',$id)->first();
        $record->is_responded = true;
        $record->save();



        return $this->support();
    }

    public function send(){
        $type =  Input::get('list');
        $emails = $this->generateEmailList($type);


        $data['heading1'] = Input::get('heading1');
        $data['heading2'] = Input::get('heading2');
        $data['text2'] = Input::get('text2');
        $data['text1'] = Input::get('text1');
        $data['button'] = Input::get('button');
        $data['buttonURL'] = Input::get('buttonURL');
        $data['subj'] = Input::get('subject');

        if ($data['heading1']  == "nothing"){$data['heading1']  = null;}
        if ($data['heading2']  == "nothing"){$data['heading2']  = null;}
        if ($data['text2']  == "nothing"){$data['text2']  = null;}
        if ($data['text1']  == "nothing"){$data['text1']  = null;}
        if ($data['button']  == "nothing"){$data['button']  = null;}
        if ($data['buttonURL']  == "nothing"){$data['buttonURL']  = null;}


        Mail::to($emails)->queue(new generic($data));

        flash()->overlay("Good Luck with the $$$", 'Good luck');
        return view("admin.mailer");

    }


    private function generateEmailList($type){
        switch ($type){
            case "All Subscribers and Users":
                return array("abdelilah.sbaai@gmail.com", "abdel.ilah.sbaai@gmail.com", "a.bdelilah.sbaai@gmail.com");
            case "All Subscribers":
            case "All Users":
            case "Subscribers Didn't register":
            case "Users Topped Up":
            case "Users Didn't Top Up":
            case "Users With Numbers":
            case "Users Without Numbers":
    }
    return "";
    }

    private function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }

    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    date_default_timezone_set("UTC");
    $now             = time();
    $unix_date         = strtotime($date);

    if(empty($unix_date)) {
        return "Bad date";
    }
    if($now > $unix_date) {
        $difference     = $now - $unix_date;
        $tense         = "ago";

    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if($difference != 1) {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] {$tense}";
}


}
