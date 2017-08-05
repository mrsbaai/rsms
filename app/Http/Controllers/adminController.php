<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\user;
use App\suppression;
use App\number;
use App\subscriber;
use App\contact;
use App\paymentlog;
use App\coupon;
use Illuminate\Support\Facades\Input;
use carbon\carbon;
use Charts;

use Mail;
use App\Mail\numbersReady;

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
                array_push($row, $record[$column]);
            }
            array_push($rows, $row);
        }

       return array('rows' => $rows, 'columns' => $columns);
    }

    public function showNumbers(){
        $records = number::all();
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email");
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
            $type = "Topups";
            $period = "24h";
        }

        $records = paymentlog::all()->where('status',"Completed")->sortByDesc('id');
        $columns =  array("id", "created_at", "payedAmount", "originalAmount", "code", "userEmail", "buyerEmail", "paymentSystemId");
        $data = $this->formatData($records,$columns);

        return view('admin.sources')->with('rows', $data['rows'])->with('columns', $data['columns'])->with('type', $type)->with('period', $period);
    }




    public function showOrders(){
        $records = number::all();
        $columns =  array("id", "number", "country", "expiration", "is_private", "network", "network_login", "network_password", "email");
        $data = $this->formatData($records,$columns);
        return view('admin.show')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }
    Public function support(){


        $records = contact::all()->where('is_responded',false)->sortByDesc('id');
        $columns =  array("id", "created_at", "name", "subject", "message", "is_support");


        $rows = [];
        foreach($records as $index => $record) {
            $row = [];
            foreach($columns as $column){
                array_push($row, $record[$column]);
            }
            array_push($rows, $row);
        }

        Return view('admin.support')->with('rows', $rows)->with('columns', $columns);
    }


    public function mailer(){
        return view("admin.mailer");
    }

    public function preview($text1, $text2, $heading1, $heading2, $button,$buttonURL){

        $heading1 =  base64_decode($heading1);
        $heading2 =  base64_decode($heading2);
        $text2 =  base64_decode($text2);
        $text1 =  base64_decode($text1);
        $button =  base64_decode($button);
        $buttonURL =  base64_decode($buttonURL);

        if ($heading1 == "nothing"){$heading1 = null;}
        if ($heading2 == "nothing"){$heading2 = null;}
        if ($text2 == "nothing"){$text2 = null;}
        if ($text1 == "nothing"){$text1 = null;}
        if ($button == "nothing"){$button = null;}
        if ($buttonURL == "nothing"){$buttonURL = null;}
        $markdown = new Markdown(view(), config('mail.markdown'));
        return $markdown->render('emails.generic', ['button' => $button, 'text1' => $text1, 'heading1' => $heading1, 'heading2' => $heading2, 'text2' => $text2, 'buttonURL' => $buttonURL]);
    }

    public function coupon(){

        $records = coupon::all()->where('is_active',"=",true)->where('expiration', '>', Carbon::now());
        $columns =  array("id", "code", "minimum_price", "paymentsystem_id", "value", "expiration");

        $data = $this->formatData($records, $columns);
        return View('admin.coupon')->with('rows', $data['rows'])->with('columns', $data['columns']);
    }

    public function blacklists(){
        return "test";
    }

    public function give(){
        return view('admin.give');
    }

    public function send(){
        return "sending";
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
        echo '$_SERVER[\'HTTP_REFERER\'] = '.$_SERVER['HTTP_REFERER'].'<br>';
    }

    public function giveNumbers(){

        $amount = Input::get('amount');
        $email = Input::get('user_email');

        $user = user::all()->where('email','=',$email)->first();
        $name = $user->name;
        $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->shuffle()->take($amount);
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

        return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);

    }
}
