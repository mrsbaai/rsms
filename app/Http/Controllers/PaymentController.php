<?php

namespace App\Http\Controllers;
use App\paymentsystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\coupon;
use App\paypalids;
use App\User;
use App\number;
use App\paymentlog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;
use Mail;
use App\Mail\topupReceipt;
use App\Mail\numbersReady;
use PushBullet;

class PaymentController extends Controller
{


    public function  pp(){


    }

    public function ppsend(){

    }



    public function getdisposable(){

    }

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
    public function RedirectToPaymentInternal(){

        $business =  Input::get('toemail');
        $amount = Input::get('amount');

        $cmd = '_xclick';
        $item_name = "$" . $amount . " Balance TopUp";
        $currency_code = 'USD';
        $custom = "";
        $return = 'http://receive-sms.com/';
        $notify_url = 'http://receive-sms.com/ipn/paypal';
        $cancel_return = 'http://receive-sms.com/';
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
    }
    public function RedirectToPayment(){
        if (Auth::check()){
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

                    return redirect()->away($url . "?" . http_build_query($properties));
                //return $url . "?" . http_build_query($properties);

            }

        }else{
            return redirect('/login');
        }






    }

    private function GetPayPal(){


        $earlier = Carbon::now()->subDays(25);

        $paypalAccounts = paypalids::where('is_active', '=' ,true)->get();

        $logs = paymentlog::where('type', '=' ,"new_case")->where('created_at', '>', $earlier)->get();


        $ids = array();
        foreach($paypalAccounts as $paypalAccount){
            $ids[$paypalAccount['email']] = 0;
        }

        foreach($paypalAccounts as $paypalAccount){
            foreach($logs as $log){
                if ($log['accountId'] == $paypalAccount['email']){
                    $ids[$paypalAccount['email']] = $ids[$paypalAccount['email']] + 1;
                }
            }
        }


        asort($ids);


        $least_amout_of_cases = $ids[key($ids)];

        $acceptable_accounts = array();
        foreach($ids as $email => $count){
            if ($least_amout_of_cases == $count){
                array_push($acceptable_accounts, $email);
            }
        }


        //shuffle($acceptable_accounts);
        $selected_paypal_account_id = paypalids::where('email', $acceptable_accounts[0])->first();
        return $selected_paypal_account_id['paypalid'];


    }

    public function test(){
        $this->log("1.1", "", "Internal", "", "", "", "mr.chioua@gmail.com", "rahmanbegum4@gmail.com", "PayPal");

    }


    public function payzaIPN(){
        // Url address of IPN V2 handler and the identifier of the token string
        define("IPN_V2_HANDLER", "https://secure.payza.com/ipn2.ashx");
        define("TOKEN_IDENTIFIER", "token=");

        // get the token from Payza
        $token = urlencode($_POST['token']);

        // preappend the identifier string "token="
        $token = TOKEN_IDENTIFIER.$token;

        $response = '';

        // send the URL encoded TOKEN string to the Payza's IPN handler
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, IPN_V2_HANDLER);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // $response holds the response string from the Payza's IPN.
        $response = curl_exec($ch);

        curl_close($ch);

        if($response != FALSE)
        {
            if(urldecode($response) == "INVALID TOKEN")
            {
                Log::error("Payza: the token is not valid");

            }
            else
            {
                $response = urldecode($response);
                parse_str($response, $responseArray);

				if (isset($responseArray['ap_description'])){
					$description = $responseArray['ap_description'];
					$paymentSystem="Payza";
					$originalAmount = $this->getDescriptionVariables("originalAmount",$description);
					$userEmail = $this->getDescriptionVariables("userEmail",$description);
					$code = $this->getDescriptionVariables("code",$description);
					$payedAmount = $responseArray['ap_amount'];

					$transactionType = $responseArray['ap_notificationtype'];
					$transactionStatus = $responseArray['ap_transactionstate'];
					$buyerEmail = $responseArray['ap_custemailaddress'];
					$accountId = $responseArray['ap_merchant'];
					$this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);
					if ("Completed" == $transactionStatus or "On Hold" == $transactionStatus){
						$this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
					}
				}


            }
        }
        else
        {
            Log::error("something is wrong, no response is received from Payza");
        }

    }

    /**
     * @return string
     */
    public function payeerIPN(){
        if (Input::get('m_operation_id') !== null && Input::get('m_sign') !== null)
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

            $m_desc = base64_decode($m_desc);
            $originalAmount = $this->getDescriptionVariables("originalAmount",$m_desc);
            $userEmail = $this->getDescriptionVariables("userEmail",$m_desc);
            $code = $this->getDescriptionVariables("code",$m_desc);

            $payedAmount = $m_amount;

            $transactionType = $m_operation_ps;
            $transactionStatus = $m_status;

            $buyerEmail = $userEmail;
            $accountId = $m_shop;
            $paymentSystem = "Payeer";

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);


            if ($m_sign == $sign_hash && $m_status == 'success'){
                $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
            }

            return $m_orderid . "|" . $m_status;

        }

    }


    public function paypalIPN(){

        $ipn = new PaypalIPN();
        $verified = $ipn->verifyIPN();
        if ($verified) {
            $paymentSystem = "PayPal";
            if (isset($_POST["custom"])){$description = $_POST["custom"];}else{$description = "";}

            $originalAmount = $this->getDescriptionVariables("originalAmount",$description);
            $userEmail = $this->getDescriptionVariables("userEmail",$description);
            $code = $this->getDescriptionVariables("code",$description);

            if (isset($_POST["mc_fee"])){$mc_fee = $_POST["mc_fee"];}else{$mc_fee = "0";}
            if (isset($_POST["mc_gross"])){$payedAmount = $_POST["mc_gross"];}else{$payedAmount = "";}
            if (isset($_POST["txn_type"])){$transactionType = $_POST["txn_type"];}else{$transactionType = "";}
            if (isset($_POST["payment_status"])){$transactionStatus = $_POST["payment_status"];}else{$transactionStatus = "";}
            if (isset($_POST["payer_email"])){$buyerEmail = $_POST["payer_email"];}else{$buyerEmail = "";}
            if (isset($_POST["business"])){$accountId = $_POST["business"];}else{$accountId = "";}
            if (isset($_POST["payment_status"])){$payment_status = $_POST["payment_status"];}else{$payment_status = "";}
            if (isset($_POST["payment_type"])){$payment_type = $_POST["payment_type"];}else{$payment_type = "";}
            if (isset($_POST["pending_reason"])){$pending_reason = $_POST["pending_reason"];}else{$pending_reason = "";}

            if (($payment_status == 'Completed') || ($payment_status == 'Pending' && $payment_type == 'instant' && $pending_reason == 'paymentreview')){
                // successful payment -> top up

                $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem);
            }
            // loging the event

            if ($payedAmount > 0){
                $payedAmount = $payedAmount - $mc_fee;
            }

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);


        }
        header("HTTP/1.1 200 OK");
    }

public function smsver(){
        $ipn = new PaypalIPN();
        $verified = $ipn->verifyIPN();
        if ($verified) {
            $paymentSystem = "PayPal";
            if (isset($_POST["mc_gross"])){$payedAmount = $_POST["mc_gross"];}else{$payedAmount = 0;}
            if (isset($_POST["txn_type"])){$transactionType = $_POST["txn_type"];}else{$transactionType = "";}
            if (isset($_POST["payment_status"])){$transactionStatus = $_POST["payment_status"];}else{$transactionStatus = "";}
            if (isset($_POST["payer_email"])){$buyerEmail = $_POST["payer_email"];}else{$buyerEmail = "";}
            if (isset($_POST["business"])){$accountId = $_POST["business"];}else{$accountId = "";}
            if (isset($_POST["payment_status"])){$payment_status = $_POST["payment_status"];}else{$payment_status = "";}
            if (isset($_POST["payment_type"])){$payment_type = $_POST["payment_type"];}else{$payment_type = "";}
            if (isset($_POST["pending_reason"])){$pending_reason = $_POST["pending_reason"];}else{$pending_reason = "";}

            $originalAmount = $payedAmount;
            $userEmail = $buyerEmail;
            $code = "SMS-Verification.net";
            // loging the event

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem);

            if (($payment_status == 'Completed') || ($payment_status == 'Pending' && $payment_type == 'instant' && $pending_reason == 'paymentreview')){
                // successful payment -> SMS notification
                Log::info("[Payment Successful] [SMS-Verification.net] $payedAmount USD $accountId");

            }
        }
        header("HTTP/1.1 200 OK");
    }


    public function getPrice($amount=1,$period=1){
        if (Auth::check()){
            $email = Auth::user()->email;
            $numbers = number::where('is_private',true)->where('email',$email)->get();

            if (!is_numeric($amount)){$amount=1;}

            $totalAmount = $amount + count($numbers);



        }else{
            $totalAmount = $amount;
        }


        $unit_price = 9;


        if ($totalAmount < 10){
            $unit_price = 9;
        }

        if ($totalAmount >= 10){
            $unit_price = 5;
        }

        $price = $amount * $unit_price * $period;
        return $price;

    }

    public function showPrice($amount=1,$period=1){
        $price = $this->getPrice($amount,$period);
        if (Auth::check()){
            if($price > Auth::user()->balance){$isPossible = false;}else{$isPossible = true;}
        }else{
            $isPossible = false;
        }

        header('Content-type: application/json');
        return json_encode(array ('isPossible'=> $isPossible,'price'=>$price));
    }

    private function doTopup($email,$payedAmount,$originalAmount,$code,$paymentSystem){

        // check coupon
        $topup  = $originalAmount;
        $couponApplied = $this->CouponToPrice($originalAmount,$paymentSystem,$code);
        if ($couponApplied <> $payedAmount) {$topup = $payedAmount ;}
        Log::info("[Payment Successful] $topup | $email | $paymentSystem");
        // topup
        $user = User::where('email', $email)->first();
        User::where('email', "=", $email)->update(['paid' => $topup]);

        $topup  = $topup + $user['balance'];
        User::where('email', "=", $email)->update(['balance' => $topup]);
        // send receipt

        $data['name'] = $user['name'];
        $data['date'] = Carbon::now();
        $data['amount'] = $originalAmount;
        $data['finalBalance'] = $topup;
        $data['type'] = $paymentSystem;

        Mail::to($email)->send(new topupReceipt($data));

    }



    Private function log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem){


        if ($payedAmount == ''){$payedAmount = 0;}
        if ($originalAmount == ''){$originalAmount = 0;}
        if ($payedAmount !== 0 and $paymentSystem == "PayPal"){
            $pp = paypalids::where('email',$accountId)->first();
            $newBalance = $pp['balance'] + $payedAmount;
            paypalids::where('email', "=", $accountId)->update(['balance' => $newBalance]);
            $message = "From: $" . $pp['balance'] . " To: $" . $newBalance;
            PushBullet::all()->note("$accountId: Balance changed", $message);

            $pp = paypalids::where('email',$buyerEmail)->first();
            if ($pp){
                $newBalance = $pp['balance'] - $payedAmount;
                paypalids::where('email', "=", $buyerEmail)->update(['balance' => $newBalance]);
                $message = "From: $" . $pp['balance'] . " To: $" . $newBalance;
                PushBullet::all()->note("$buyerEmail: Balance changed", $message);
            }else{
                if ($payedAmount > 0){
                    $info = "
                Payed: $$payedAmount
                Receiver: $accountId
                Original: $$originalAmount
                Code: $code
                User: $userEmail
                Buyer: $buyerEmail";
                    PushBullet::all()->note("$$payedAmount $paymentSystem Payment Received", $info);
                }
            }


        }



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
