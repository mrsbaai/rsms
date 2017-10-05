<?php

namespace App\Http\Controllers;
use App\paymentsystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\coupon;
use App\paypalids;
use App\user;
use App\number;
use App\paymentlog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;
use Mail;
use App\Mail\topupReceipt;
use App\Mail\numbersReady;

class PaymentController extends Controller
{


    public function emailtest (){
        $email = "abdelilahs.sbaai@gmail.com";
        $data['name'] = "Abdelilah";
        $data['numbers'] = array(array("111111111111111", "US", "International", Carbon::now()), array("222222222222222", "US", "International", Carbon::now()));
        Mail::to($email)->send(new numbersReady($data));
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


    public function payzaIPN(){

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

    /**
     * @return string
     */
    public function payeerIPN(){
        Log::info("im a payeer notification");
        if (isset(Input::get('m_operation_id')) && isset(Input::get('m_sign')))
        {
                $m_key = 'nirvana';
                $m_operation_id = Input::get('m_operation_id');
                $m_operation_ps = Input::get('m_operation_ps');
                $m_operation_date = Input::get('m_operation_date');
                $m_operation_pay_date = Input::get('m_operation_pay_date');
                $m_shop = Input::get('m_shop');
                $m_orderid = Input::get('m_orderid');
                $m_amount = Input::get('m_amount');
                $m_curr = Input::get('m_curr');
                $m_desc = Input::get('m_desc');
                $m_status = Input::get('m_status');
                $m_sign = Input::get('m_sign');

            $arHash = array(
                $m_operation_id,
                $m_operation_ps,
                $m_operation_date,
                $m_operation_pay_date,
                $m_shop,
                $m_orderid,
                $m_amount,
                $m_curr,
                $m_desc,
                $m_status,
                $m_key);

            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            if ($m_sign == $sign_hash && $m_status == 'success'){
                $successful_payment = true;


            }

            return $m_orderid . "|" . $m_status;

        }

    }


    public function paypalIPN(){
        Log::info("im a paypal notification");
        $ipn = new PaypalIPN();
        $ipn->useSandbox();
        $verified = $ipn->verifyIPN();
        if ($verified) {
            Log::info("im a paypal notification");
            $paymentSystem = "PayPal";
            $description = $_POST["custom"];

            $originalAmount = $this->getDescriptionVariables("originalAmount",$description);
            $userEmail = $this->getDescriptionVariables("userEmail",$description);
            $code = $this->getDescriptionVariables("code",$description);

            $payedAmount = $_POST["mc_gross"];
            $transactionType = $_POST["txn_type"];
            $transactionStatus = $_POST["payment_status"];
            $buyerEmail = $_POST["payer_email"];

            $accountId = $_POST["business"];



            // loging the event

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);

            if (($_POST["payment_status"] == 'Completed') || ($_POST["payment_status"] == 'Pending' && $_POST["payment_type"] == 'instant' && $_POST["pending_reason"] == 'paymentreview')){
                // successful payment -> top up
                $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
            }
        }
        header("HTTP/1.1 200 OK");
    }


    public function getPrice($amount=1,$period=1){

        $email = Auth::user()->email;
        $numbers = number::where('is_private',true)->where('email',$email)->get();
        $userAmount = $amount + count($numbers);

        $unit_price = 9;


        if ($userAmount < 10){
            $unit_price = 9;
        }

        if ($userAmount >= 10){
            $unit_price = 5;
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

        $data['name'] = $user['name'];
        $data['date'] = Carbon::now();
        $data['amount'] = $originalAmount;
        $data['finalBalance'] = $topup;
        $data['type'] = $paymentSystem;

        Mail::to($email)->send(new topupReceipt($data));

    }

    public function test(){

        $email = "abdelilah.sbaai@gmail.com";
        $data['name'] = "Abdelilah";
        $data['numbers'] = array(array("111111111111111", "US", "International", Carbon::now()), array("222222222222222", "US", "International", Carbon::now()));
        Mail::to($email)->send(new numbersReady($data));

        return "ok";

    }

    Private function log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem){

        $user = User::where('email',$userEmail)->first();
        $source = $user['source'];

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
                "source"=>$source,
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
