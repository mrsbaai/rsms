<?php

namespace App\Http\Controllers;
use App\paymentsystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\coupon;
use App\paypalids;
use App\user;
use App\paymentlog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;
use Mail;
use App\Mail\topupReceipt;
include(dirname(dirname(__FILE__)).'/src/IpnListener.php');
use dezlov\PayPal\IpnListener;

class PaymentController extends Controller
{
    //
    Public function getProductName($quantity, $days, $country){
        switch ($days) {
            case 3:
                $period = "3 Days";
                break;
            case 30:
                $period = "1 Month";
                break;
            case 180:
                $period = "6 Months";
                break;
            case 360:
                $period = "1 Year";
                break;
        }

        if ($quantity == 1){$str = "Number";}else{$str = "Numbers";}

        $name = $quantity . " " . strtoupper($country) . " " . "Phone " . $str . " (" . $period . ")";
        return $name;

    }

    public function applyCoupon($amount,$paymentsystem,$code){
        header('Content-type: application/json');
        $coupon = coupon::where('code', '=', $code)->where('paymentsystem_id', '=', $paymentsystem)->first();
        if ($coupon === null) {

            return json_encode(array ('message'=>'Invalid Coupon, or Cannot be applied to the selected payment system.','price'=>$amount));

        }else{
            if ($coupon['is_active'] == false){return json_encode(array ('message'=>'This Coupon is not active!','price'=>$amount));}
            if ($coupon['expiration'] < Carbon::now()){return json_encode(array ('message'=>'This Coupon has been expired!','price'=>$amount));}
            if ($amount < $coupon['minimum_price']){return json_encode(array ('message'=>'The minimum amount to apply this coupon is: $' . $coupon['minimum_price'],'price'=>$amount));}


            $amount = $amount - ($amount * $coupon['value'] / 100) ;
            return json_encode(array ('message'=> $coupon['value'] . '%' . ' off applied!','price'=>$amount));
        }

    }

    private function CouponToPrice($amount,$paymentsystem,$code){
        header('Content-type: application/json');
        $coupon = coupon::where('code', '=', $code)->where('paymentsystem_id', '=', $paymentsystem)->first();
        if ($coupon === null) {
            return $amount;
        }else{
            if ($coupon['is_active'] == false){return $amount;}
            if ($coupon['expiration'] < Carbon::now()){return $amount;}
            if ($amount < $coupon['minimum_price']){return $amount;}

            $amount = $amount - ($amount * $coupon['value'] / 100) ;
            return $amount;
        }

    }
    public function RedirectToPayment(){


        $email = Auth::user()->email;
        $amountOriginal = Input::get('amount');
        $type = Input::get('type');
        $code = Input::get('coupon');
        $amountToPay = $this->CouponToPrice($amountOriginal,$type,$code);
        $description = "[" . $amountOriginal  . "$ Balance Top Up] [User: " . $email  . "]";
        if ($code <> "" and $code <> null){$description = $description . " [Coupon: " . $code  . "]";}

        switch ($type) {
                case "PayPal":
                    $cmd = '_xclick';
                    $business = $this->GetPayPal();
                    $item_name = "$" . $amountOriginal . " Balance Top Up";
                    $currency_code = 'USD';
                    $custom = $description;
                    $amount = $amountToPay;
                    $return = 'http://receive-sms.com/success';
                    $notify_url = 'http://receive-sms.com/ipn/paypal';
                    $cancel_return = 'http://receive-sms.com/fail';
                    $properties = array(
                        "cmd"=>$cmd,
                        "business"=>$business,
                        "item_name"=>$item_name,
                        "currency_code"=>$currency_code,
                        "custom"=>$custom,
                        "amount"=>$amount,
                        "return"=>$return,
                        "notify_url"=>$notify_url,
                        "cancel_return"=>$cancel_return
                    );
                    $url = "https://www.paypal.com/cgi-bin/webscr";
                    return redirect()->away($url . "?" . http_build_query($properties));

            case "Payeer":
                $m_shop = '88704496';
                $m_orderid = rand(1111111, 9999999);
                $m_amount = number_format($amountToPay, 2, '.', '');
                $m_curr = 'USD';
                $desc = $description;
                $m_desc = base64_encode($desc);
                $m_key = 'nirvana';

                $arHash = array(
                    $m_shop,
                    $m_orderid,
                    $m_amount,
                    $m_curr,
                    $m_desc,
                    $m_key
                );
                $sign = strtoupper(hash('sha256', implode(':', $arHash)));

                $url = "https://payeer.com/merchant/";
                $properties = array(
                    "m_email"=>$email,
                    "m_shop"=>$m_shop,
                    "m_orderid"=>$m_orderid,
                    "m_amount"=>$m_amount,
                    "m_curr"=>$m_curr,
                    "m_desc"=>$m_desc,
                    "m_sign"=>$sign


                );
                return redirect()->away($url . "?" . http_build_query($properties));
            case "Payza":

                $url = "https://secure.payza.com/checkout";

                $ap_merchant = "abdelilahsbaai@gmail.com";
                $ap_purchasetype = "service";
                $ap_itemname = "$" . $amountOriginal . " Balance Top-up";
                $ap_amount = $amountToPay;
                $ap_currency = "USD";
                $ap_description = $description;
                $ap_ipnversion = "2";

                $ap_alerturl = "https://receive-sms.com/ipn/payza";
                $ap_returnurl = "https://receive-sms.com/succsess";
                $ap_cancelurl = "https://receive-sms.com/fail";


                $properties = array(
                    "ap_merchant"=>$ap_merchant,
                    "ap_purchasetype"=>$ap_purchasetype,
                    "ap_itemname"=>$ap_itemname,
                    "ap_amount"=>$ap_amount,
                    "ap_currency"=>$ap_currency,
                    "ap_description"=>$ap_description,
                    "ap_ipnversion"=>$ap_ipnversion,
                    "ap_alerturl"=>$ap_alerturl,
                    "ap_returnurl"=>$ap_returnurl,
                    "ap_cancelurl"=>$ap_cancelurl
                );

                //return redirect()->away($url . "?" . http_build_query($properties));
                return $url . "?" . http_build_query($properties);

        }





    }

    private function GetPayPal(){


        $earlier = Carbon::now()->subDays(20);

        $paypalids = paypalids::where('is_active', '=' ,true)->get();

        $logs = paymentlog::where('type', '=' ,"new_case")->where('created_at', '>', $earlier)->get();


        $ids = array();
        foreach($paypalids as $paypalid){
            $ids[$paypalid['paypalid']] = 0;
        }

        foreach($paypalids as $paypalid){
            foreach($logs as $log){
                if ($log['account_id'] == $paypalid['paypalid']){
                    $ids[$paypalid['paypalid']] = $ids[$paypalid['paypalid']] + 1;
                }
            }
        }

        asort($ids);
        return key($ids);


    }

    public function IPN($type, Request $request){

        switch ($type){
            case "paypal":
<<<<<<< HEAD


                $listener = new IpnListener();
                $listener->use_sandbox = true;
                $error = null;
                $verified = $listener->tryProcessIpn(null, $error);
                if ($verified)
                    Log::info('IPN verified successfully.'.PHP_EOL);
                else
                Log::info('IPN error: '.$error.PHP_EOL);
=======
                $payment_status = $_POST['payment_status'];
                Log::info($payment_status);
                
                header("HTTP/1.1 200 OK");
>>>>>>> parent of 7a18348... paypal ipn test

            case "payeer":


                if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
                {
                    $m_key = 'nirvana';
                    $arHash = array($_POST['m_operation_id'],
                        $_POST['m_operation_ps'],
                        $_POST['m_operation_date'],
                        $_POST['m_operation_pay_date'],
                        $_POST['m_shop'],
                        $_POST['m_orderid'],
                        $_POST['m_amount'],
                        $_POST['m_curr'],
                        $_POST['m_desc'],
                        $_POST['m_status'],
                        $m_key);

                    $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
                    $description = base64_decode($_POST['m_desc']);
                    $paymentSystem="Payeer";

                    $originalAmount = $this->getDescriptionVariables("originalAmount",$description);
                    $userEmail = $this->getDescriptionVariables("userEmail",$description);
                    $code = $this->getDescriptionVariables("code",$description);

                    $payedAmount = $_POST['m_amount'];

                    $transactionType = $_POST['m_operation_ps'];
                    $transactionStatus = $_POST['m_status'];

                    $buyerEmail = $_POST['m_amount'];
                    $accountId = $_POST['m_shop'];


                    $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);

                    if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success'){
                        // successful payment -> top up

                        $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
                    }else{
                        echo $_POST['m_orderid']."|".$_POST['m_status'];
                        exit;
                    }
                }



            case "payza":

                if (isset($_POST['ap_securitycode'])){
                    if ($_POST['ap_securitycode'] == "MuzNfecPcABJZfqu"){
                        $description = $_POST['ap_description'];
                        $paymentSystem="Payza";
                        $originalAmount = $this->getDescriptionVariables("originalAmount",$description);
                        $userEmail = $this->getDescriptionVariables("userEmail",$description);
                        $code = $this->getDescriptionVariables("code",$description);
                        $payedAmount = $_POST['ap_amount'];

                        $transactionType = $_POST['ap_notificationtype'];
                        $transactionStatus = $_POST['ap_transactionstate'];

                        $buyerEmail = $_POST['ap_custemailaddress'];
                        $accountId = $_POST['ap_merchant'];

                        $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);
                        if ("Completed" == $transactionStatus){
                            $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
                        }
                    }
                }

        }


    }



    public function getPrice($amount=1,$period=1){

        $unit_price = 9;

        if ($amount == 1){
            $unit_price = 9;
        }

        if ($amount > 1 and $amount < 6){
            $unit_price = 5;
        }

        if ($amount >= 6){
            $unit_price = 4.5;
        }

        $price = $amount * $unit_price * $period;
        return $price;

    }

    public function showPrice($amount=1,$period=1){
        $price = $this->getPrice($amount,$period);

        if($price > Auth::user()->balance){$isPossible = false;}else{$isPossible = true;}
        header('Content-type: application/json');
        return json_encode(array ('isPossible'=> $isPossible,'price'=>$price));
    }

    private function doTopup($email,$payedAmount,$originalAmount,$code,$paymentSystem){

        // check coupon

        $topup  = $originalAmount;
        $couponApplied = $this->CouponToPrice($originalAmount,$paymentSystem,$code);
        if ($couponApplied <> $payedAmount) {$topup = $payedAmount ;}

        // topup
        $user = user::where('email', "=", $email)->firstorfail();

        $topup  = $topup + $user['balance'];

        user::where('email', "=", $email)->update(['balance' => $topup]);

        // send receipt

        Mail::to($email)->send(new topupReceipt($email));

    }

    public function test(){

        $description = "[10$ Balance Top Up] [User: abdelilahsbaai@gmail.com]";
        return $this->getDescriptionVariables("userEmail",$description);


    }

    Private function log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem){

        $p = paymentsystem::where('system', '=', $paymentSystem)->first();
        $paymentSystemId = $p['id'];

        {


            $log = array(
                "payedAmount"=>$payedAmount,
                "originalAmount"=>$originalAmount,
                "code"=>$code,
                "type"=>$transactionType,
                "status"=>$transactionStatus,
                "userEmail"=>$userEmail,
                "buyerEmail"=>$buyerEmail,
                "accountId"=>$accountId,
                "paymentSystemId"=>$paymentSystemId,
                "created_at"=>Carbon::now()
            );

            DB::table('paymentlog')->insert($log);

        }
    }

    private function getDescriptionVariables($variable, $description){

        switch ($variable){
            case "originalAmount":
                $re = '/\[(.*?)\$/';
                preg_match($re, $description, $matches);
                if(array_key_exists(1, $matches)){
                    return  $matches[1];
                }
            case "userEmail":
                $re = '/User: (.*?)\]/';
                preg_match($re, $description, $matches);
                if(array_key_exists(1, $matches)){
                    return  $matches[1];
                }
            case "code":
                $re = '/Coupon: (.*?)\]/';
                preg_match($re, $description, $matches);
                if(array_key_exists(1, $matches)){
                    return  $matches[1];
                }

        }
        return "";

    }
}
