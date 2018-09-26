<?php

namespace App\Http\Controllers;
use App\coupon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use DB;
use App\Quotation;
use App\number;
use App\user;
use App\paymentlog;
use App\message;
use Mail;
use App\Mail\newCoupon;
use App\Mail\numberRemovalNotification;

use carbon\carbon;

use Illuminate\Mail\Markdown;

use Log;
use App\Libraries\Session;
use use simplepush;
class pagesController extends Controller
{
    Public function home(){

        if (Auth::check()){
            return redirect('/inbox');
        }else{

            $messageController = new messagesController();
            $messages = $messageController->getPublicMessages(null);
            $lastMessage =  $messages[0]['id'];
            $numbers = number::all()->where('is_private',false);

            return view('home')->with('numbers', $numbers)->with('messages', $messages)->with('lastMessage', $lastMessage);
        }
    }

    Public function showTag($tag){
        if (!is_string($tag)){
            return redirect('/home');
        }
        if (Auth::check()){
            return redirect('/inbox');
        }else{
            $messageController = new messagesController();
            $messages = $messageController->getPublicMessages(null,$tag);
            $lastMessage =  $messages[0]['id'];
            $numbers = number::all()->where('is_private',false);

            return view('showTag')->with('numbers', $numbers)->with('tag', $tag)->with('messages', $messages)->with('lastMessage', $lastMessage);
        }
    }


    Public function contact(){
        if (Auth::check()){
            return redirect('/support');
        }else{
            return view('contact');
        }

    }


    Public function login(){
        if (Auth::check()){
            return view('inbox');
        }else{
            return view('auth.login');
        }

    }

    public function add(){
        $userController = new userController;
        $user =  array("first_name"=>"Abdelilah", "last_name"=>"Sbaai", "callback_url"=>"http://google.com", "email"=>"test@gmail.com", "password"=>"9915");
        $userController->add($user);
        //return view('test');
    }

    public function tester(){

        //$users = User::all();

        //foreach($users as $user){
            //$userController = new \App\Http\Controllers\userController;
            //$userController->SendTopupEmail($user['id']);
        //}

        //$userController = new userController;
        //return $userController->CouponTwoDays(30);
        //$coupon = coupon::all();
        //return response()->json($coupon);


            //$markdown = new Markdown(view(), config('mail.markdown'));
            //return $markdown->render('emails.topupNeeded');

        //flash('Please check your email and verify your address')->warning();
        //return view('contact');


        //$data['name'] = "master";
       // Mail::to("abdelilah.sbaai@gmail.com")->send(new numberRemovalNotification($data));


        $session = new Session();

        $message = $session->getInitialText();

        echo "m: " . $message;



    }
    public function testing(){
        $numbers = number::all()->where('password', null);

        foreach ($numbers as $number) {
            if ($number['network'] = "voxeo"){
                echo $number['network_login'] . " - " . $number['network_password'] . "<br/>";
            }
        }
    }

    public function test(){
		Simplepush::send("H5ZAU6", "title", "message", "event");

    }

    public function valid_email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = explode('@', $email)[1];
        if (!checkdnsrr( $domain, 'mx')) {
            return false;
        }

        $socket = @fsockopen($domain, 25, $errno, $errstr, 30);
        if (!$socket) {
            return false;
        }

        $str = @fgets($socket, 515); // Clear response, get connected message and optionally verify it.

        fwrite($socket, 'HELLO \r\n');
        $str = @fgets($socket, 515); // Clear response.

        fwrite($socket, 'MAIL FROM: <test@gmail.com>\r\n');
        $str = @fgets($socket, 515); // Clear response.

        fwrite($socket, "RCPT TO: <${email}>\r\n");
        $str = @fgets($socket, 515); // Clear response.

        @fclose($socket);

        if (stripos($str, 'OK') === false) {
            return false;
        }

        if (!mail($email, '[Ignore] Testing if email address exists', 'message body...')) {
            return false;
        }


        return true;
    }


    public function pricing(){
        return view('pricing');
    }
    Public function faqs(){
        return view('faqs');
    }
    Public function recover(){
        return view('recover');
    }


    public function api(){
        return view('api');
    }
    Public Function showMessages($number){
        if (Auth::check()){
            return redirect('/inbox');
        }else{

            $messageController = new messagesController();
            $messages = $messageController->getPublicMessages($number);
            $lastMessage =  $messages[0]['id'];
            return view('home_number_inbox')->with('current', $number)->with('messages', $messages)->with('lastMessage', $lastMessage);
        }
    }

}
