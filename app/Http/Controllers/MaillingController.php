<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Mail;
use App\number;
use App\coupon;
use App\user;
use App\paymentsystem;
use App\Mail\numberRemovalNotification;
use App\Mail\topupNeeded;
use App\Mail\newCoupon;

use Carbon\Carbon;

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
                        $diff = $now->diffInDays($date, false);

                        $logAction = "[Auto Mail] " . $user["email"] . " | ";
                        Log::info($logAction);

                        $count = $count + 2;
                        $when = Carbon::now()->addMinutes($count);
                        switch ($diff) {
                            case 14:
                                //Mail::to($user["email"])->later($when, new topupNeeded());

                                $logAction = "[Auto Mail] " . $user["email"] . " | topupNeeded";
                                Log::info($logAction);
                                break;
                            case 10:
                                //Mail::to($user["email"])->later($when, new topupNeeded());

                                $logAction = "[Auto Mail] " . $user["email"] . " | topupNeeded";
                                Log::info($logAction);
                                break;
                            case 7:
                                //Mail::to($user["email"])->later($when, new topupNeeded());

                                $logAction = "[Auto Mail] " . $user["email"] . " | topupNeeded";
                                Log::info($logAction);
                                break;
                            case 4:
                                //Mail::to($user["email"])->later($when, new topupNeeded());

                                $logAction = "[Auto Mail] " . $user["email"] . " | topupNeeded";
                                Log::info($logAction);
                                break;
                            case 1:
                                //Mail::to($user["email"])-

                                $logAction = "[Auto Mail] " . $user["email"] . " | topupNeeded";
                                Log::info($logAction);
                                break;
                            case 3:
                                $data['name'] = $user['name'];
                                //Mail::to($user["email"])->later($when, new numberRemovalNotification($data));

                                $logAction = "[Auto Mail] " . $user["email"] . " | numberRemovalNotification";
                                Log::info($logAction);
                                break;
                            case 5:
                                $expiration = Carbon::now()->addDays(2);
                                $data['subj'] = "<<Receive-SMS>> Get 30% Off Coupon!";
                                $data['header'] = "Get a 30% Off All Your Top Ups!";
                                $data['coupon'] = $this->RandomCoupon(30,$expiration);
                                $data['date'] = $expiration;
                                //Mail::to($user["email"])->later($when, new newCoupon($data));

                                $logAction = "[Auto Mail] " . $user["email"] . " | Get 30% Off Coupon!";
                                Log::info($logAction);
                                break;
                            case 2:
                                $expiration = Carbon::now()->addDays(2);
                                $data['subj'] = "<<Receive-SMS>> Biggest Sell Out 50% Discount!";
                                $data['header'] = "Get a 50% Off All Your Top Ups!";
                                $data['coupon'] = $this->RandomCoupon(50,Carbon::now()->addDays(2));
                                $data['date'] = $expiration;
                                //Mail::to($user["email"])->later($when, new newCoupon($data));

                                $logAction = "[Auto Mail] " . $user["email"] . " |  Biggest Sell Out 50% Discount!";
                                Log::info($logAction);
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
