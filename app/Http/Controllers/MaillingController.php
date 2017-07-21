<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Mail;
use App\number;
use App\coupon;
use App\user;
use App\paymentsystem;
use App\Mail\numberRemovalNotification;
use App\Mail\topupNeeded;
use App\Mail\newCoupon;

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
        if ($nextBills){
            $count = 0;
            foreach($nextBills as $nextBill){
                foreach($nextBill as $date => $amount){
                    if ($amount < $balance){
                        $date = Carbon::parse($date);
                        $diff = $now->diffInDays($date);
                        echo $user["email"] . " -> " . $diff;
                        $count = $count + 2;
                        $when = Carbon::now()->addMinutes($count);
                        switch ($diff) {
                            case 14:
                                echo "---" . $diff . "(14)---";
                                Mail::to($user["email"])->later($when, new topupNeeded());
                                break;
                            case 10:
                                echo "---" . $diff . "(10)---";
                                Mail::to($user["email"])->later($when, new topupNeeded());
                                break;
                            case 7:
                                echo "---" . $diff . "(7)---";
                                Mail::to($user["email"])->later($when, new topupNeeded());
                                break;
                            case 4:
                                echo "---" . $diff . "(4)---";
                                Mail::to($user["email"])->later($when, new topupNeeded());
                                break;
                            case 1:
                                echo "---" . $diff . "(1)---";
                                Mail::to($user["email"])->later($when, new topupNeeded());
                                break;
                            case 3:
                                echo "---" . $diff . "(3)---";
                                $data['name'] = $user['name'];
                                Mail::to($user["email"])->later($when, new numberRemovalNotification($data));
                                break;
                            case 5:
                                echo "---" . $diff . "(5)---";
                                $expiration = Carbon::now()->addDays(2);
                                $data['subj'] = "<<Receive-SMS>> Get 30% Off Coupon!";
                                $data['header'] = "Get a 30% Off All Your Top Ups!";
                                $data['coupon'] = $this->RandomCoupon(30,$expiration);
                                $data['date'] = $expiration;
                                Mail::to($user["email"])->later($when, new newCoupon($data));
                                break;
                            case 2:
                                echo "---" . $diff . "(2)---";
                                $expiration = Carbon::now()->addDays(2);
                                $data['subj'] = "<<Receive-SMS>> Biggest Sell Out 50% Discount!";
                                $data['header'] = "Get a 50% Off All Your Top Ups!";
                                $data['coupon'] = $this->RandomCoupon(50,Carbon::now()->addDays(2));
                                $data['date'] = $expiration;
                                Mail::to($user["email"])->later($when, new newCoupon($data));
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
}
