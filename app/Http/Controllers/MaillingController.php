<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Mail;
use App\number;
use App\coupon;
use App\user;
use App\subscriber;
use App\paymentsystem;
use App\Mail\numberRemovalNotification;
use App\Mail\topupNeeded;
use App\Mail\generic;
use App\Mail\newCoupon;
use Log;

use App\pendinglist;
use App\suppression;

use carbon\carbon;

class MaillingController extends Controller
{


    public function NextBills($user_id){
        $user = User::whereid($user_id)->first();
        $numbers = Number::all()->where('is_private',true)->where('email', $user['email']);
        $expirations = array();
        foreach($numbers as $number){
            array_push($expirations, $number->expiration);
        }
        $expirationCounts = array_count_values($expirations);

        $paymentcontroller = new PaymentController();
        $NextBills = array();

        foreach($expirationCounts as $date => $count){

            $bill = array($date => $paymentcontroller->getPrice($count,'1'));
            array_push($NextBills, $bill);
        }
        return $NextBills;
    }

    public function SendTopupEmail($user_id){
        $user = User::whereid($user_id)->first();
        $balance = $user["balance"];
        $now = Carbon::now();
        $nextBills = $this->NextBills($user_id);
        $lastSentMail = "";
        if ($nextBills){
            $count = 0;
            foreach($nextBills as $nextBill){
                foreach($nextBill as $date => $amount){
                    if ($amount < $balance){
                        $date = Carbon::parse($date);
                        $diff = $now->diffInDays($date, false);
                        $count = $count + 2;
                        $when = Carbon::now()->addMinutes($count);
                        switch ($diff) {
                            case 12:
                                if ($lastSentMail !== $user["email"]){
                                    Mail::to($user["email"])->later($when, new topupNeeded());
                                    $lastSentMail = $user["email"];
                                }

                                break;
                            case 8:
                                if ($lastSentMail !== $user["email"]){
                                    Mail::to($user["email"])->later($when, new topupNeeded());
                                    $lastSentMail = $user["email"];

;
                                }


                                break;
                            case 4:
                                if ($lastSentMail !== $user["email"]){
                                    Mail::to($user["email"])->later($when, new topupNeeded());
                                    $lastSentMail = $user["email"];

                                }

                                break;
                            case 3:
                                if ($lastSentMail !== $user["email"]){
                                    $data['name'] = $user['name'];
                                    Mail::to($user["email"])->later($when, new numberRemovalNotification($data));
                                    $lastSentMail = $user["email"];


                                }

                                break;
                            case 7:
                                if ($lastSentMail !== $user["email"]){
                                    $expiration = Carbon::now()->addDays(2);
                                    $data['subj'] = "<<Receive-SMS>> Get 30% Off Coupon!";
                                    $data['header'] = "Get a 30% Off All Your Top Ups!";
                                    $data['coupon'] = $this->RandomCoupon(30,$expiration);
                                    $data['date'] = $expiration;
                                    Mail::to($user["email"])->later($when, new newCoupon($data));

                                }

                                break;
                            case 1:
                                if ($lastSentMail !== $user["email"]){
                                    $expiration = Carbon::now()->addDays(2);
                                    $data['subj'] = "<<Receive-SMS>> Biggest Sell Out 50% Discount!";
                                    $data['header'] = "Get a 50% Off All Your Top Ups!";
                                    $data['coupon'] = $this->RandomCoupon(50,Carbon::now()->addDays(2));
                                    $data['date'] = $expiration;
                                    Mail::to($user["email"])->later($when, new newCoupon($data));
                                    $lastSentMail = $user["email"];
                                }

                                break;
                        }
                    }

                }
            }
        }else{
            return;
        }
    }
    public function RandomCoupon($value,$expiration){
        $code = substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,6);
        $paymentsystems = paymentsystem::all();
        foreach ($paymentsystems as $paymentsystem){
            $newCoupon = new coupon();
            $newCoupon->code = $code;
            $newCoupon->value = $value;
            $newCoupon->paymentsystem_id = $paymentsystem['id'];
            $newCoupon->expiration = $expiration;
            $newCoupon->save();
        }

        return $code;
    }

    public function makeList(){

        $data['subj'] = Input::get('subject');
        $data['sendingdate'] = carbon::parse(Input::get('sendingdate'));


        $data['heading1'] = Input::get('heading1');
        $data['heading2'] = Input::get('heading2');
        $data['text2'] = Input::get('text2');
        $data['text1'] = Input::get('text1');
        $data['heading3'] = Input::get('heading3');
        $data['heading4'] = Input::get('heading4');
        $data['text3'] = Input::get('text3');
        $data['text4'] = Input::get('text4');
        $data['img1'] = Input::get('img1');
        $data['img2'] = Input::get('img2');

        $data['button1'] = Input::get('button1');
        $data['button2'] = Input::get('button2');
        $data['button3'] = Input::get('button3');

        $data['buttonURL1'] = Input::get('buttonURL1');
        $data['buttonURL2'] = Input::get('buttonURL2');
        $data['buttonURL3'] = Input::get('buttonURL3');

        if ($data['heading1']  == "nothing"){$data['heading1']  = null;}
        if ($data['heading2']  == "nothing"){$data['heading2']  = null;}
        if ($data['text1']  == "nothing"){$data['text1']  = null;}
        if ($data['text2']  == "nothing"){$data['text2']  = null;}
        if ($data['heading3']  == "nothing"){$data['heading3']  = null;}
        if ($data['heading4']  == "nothing"){$data['heading4']  = null;}
        if ($data['text3']  == "nothing"){$data['text3']  = null;}
        if ($data['text4']  == "nothing"){$data['text4']  = null;}
        if ($data['button1']  == "nothing"){$data['button1']  = null;}
        if ($data['buttonURL1']  == "nothing"){$data['buttonURL1']  = null;}
        if ($data['button2']  == "nothing"){$data['button2']  = null;}
        if ($data['buttonURL2']  == "nothing"){$data['buttonURL2']  = null;}
        if ($data['button3']  == "nothing"){$data['button3']  = null;}
        if ($data['buttonURL3']  == "nothing"){$data['buttonURL3']  = null;}
        if ($data['img1']  == "nothing"){$data['img1']  = null;}
        if ($data['img2']  == "nothing"){$data['img2']  = null;}


        $type =  Input::get('list');
        $emails = $this->generateEmailList($type);


        foreach($emails as $email) {

            $pendinglist = new pendinglist();
            $pendinglist->sendingdate = $data['sendingdate'];
            $pendinglist->email = $email;
            $pendinglist->subject = $data['subj'];

            $pendinglist->heading1 = $data['heading1'];
            $pendinglist->heading2 = $data['heading2'];
            $pendinglist->heading3 = $data['heading3'];
            $pendinglist->heading4 = $data['heading4'];
            $pendinglist->text1 = $data['text1'];
            $pendinglist->text2 = $data['text2'];
            $pendinglist->text3 = $data['text3'];
            $pendinglist->text4 = $data['text4'];
            $pendinglist->button1 = $data['button1'];
            $pendinglist->button2 = $data['button2'];
            $pendinglist->button3 = $data['button3'];
            $pendinglist->buttonURL1 = $data['buttonURL1'];
            $pendinglist->buttonURL2 = $data['buttonURL2'];
            $pendinglist->buttonURL3 = $data['buttonURL3'];
            $pendinglist->img1 = $data['img1'];
            $pendinglist->img2 = $data['img2'];

            $pendinglist->save();


        }



        flash()->overlay("Pending List Made", 'Good luck');
        return view("admin.mailer");

    }

    public function SendList(){

        $pendinglist = pendinglist::all();
        foreach($pendinglist as $entry){
            if(carbon::now()->gte(carbon::parse($entry['sendingdate']))){

                Mail::to($entry['email'])->queue(new generic($entry));
                echo $entry['subject'] . " -> " . $entry['email'] . "<br>   ";
                $entry->delete();
            }

        }

    }


    private function generateEmailList($type){


        $plucked = suppression::all()->pluck('email');
        $suppression =  $plucked->all();
        $list = array();
        if ($type == "All Subscribers and Users") {
            $plucked1 = subscriber::all()->where("confirmed","=",true)->pluck('email');
            $plucked2 = user::all()->pluck('email');
            $list1 =  $plucked1->all();
            $list2 = $plucked2->all();
            $list = array_unique(array_merge($list1,$list2), SORT_REGULAR);
        }
        if ($type == "All Subscribers") {
            $plucked = subscriber::all()->where("confirmed","=",true)->pluck('email');
            $list =  $plucked->all();
        }
        if ($type == "All Users") {
            $plucked = user::all()->where("confirmed","=",true)->pluck('email');
            $list =  $plucked->all();
        }
        if ($type == "Subscribers Didn't register") {
            $list = array('mrchioua@gmail.com','a.b.delilahsbaai@gmail.com');
        }


        $list = array_diff($list, $suppression);

        $filterd_emails = array();
        foreach($list as $email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $filterd_emails[] = $email;
            }
        }


        return $filterd_emails;
    }
}
