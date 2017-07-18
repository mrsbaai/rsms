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

        $users = User::all();

        foreach($users as $user){
            echo $user['id'];
        }
        return;
        //$userController = new userController;
        //return $userController->CouponTwoDays(30);
        //$coupon = coupon::all();
        //return response()->json($coupon);



    }
    public function test($days){

        message::truncate();
        $total = 4000;
        for ($i = 1; $i <= $days; $i++) {
            $total = $total + (($total * 0.45) /100);
        }

        $d = ($total * 0.45) /100;
        $m = $d * 30;

        return 'If You invert $' . $total . ' After ' . $days . ' days You will be having $' . $total . ' invested and generating $' . $d . ' a day ($' . $m . ' a month).';
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
    public function admin (){
        return view('admin');
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
