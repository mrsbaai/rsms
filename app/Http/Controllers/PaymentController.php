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


class PaymentController extends Controller
{


    public function ppdisposable(){
		$pp = paypalids::where('is_disposable', true)->where('is_active', true)->first();
		if ($pp and $pp['balance'] >= 0){
			 return $pp['paypalid'];
		}else{
			return $this->GetPayPal();
		}
       
    }

    public function pp(){
        $message = "test message";
        $url =  "https://sms-gateway-dev.sjmex.io/api/sms" . "?sender=111111&receiver=22222&message=" . $message;
            $url =  rawurlencode($url);
            return $url;
		//return $this->GetPayPal();
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
        $custom = "internal";
        //$return = 'http://receive-sms.com/';
        $notify_url = 'http://lehbabi.com/paypal';
        //$cancel_return = 'http://receive-sms.com/';
        $properties = array(
            "cmd"=>$cmd,
            "business"=>$business,
            "item_name"=>$item_name,
            "currency_code"=>$currency_code,
            "custom"=>$custom,
            "amount"=>$amount,
            //"return"=>$return,
            "notify_url"=>$notify_url,
            //"cancel_return"=>$cancel_return
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
					if ($amountToPay < 100){
						$business  = $this->GetPayPal();
					}else{
						$business  = $this->ppdisposable();
					}
                    $item_name = "$" . $amountOriginal . " Balance Top Up";
                    $currency_code = 'USD';
                    $custom = $description;
                    $amount = $amountToPay;
                    //$return = 'http://receive-sms.com/success';
                    $notify_url = 'http://lehbabi.com/paypal';
                    //$cancel_return = 'http://receive-sms.com/fail';
                    $properties = array(
                        "cmd"=>$cmd,
                        "business"=>$business,
                        "item_name"=>$item_name,
                        "currency_code"=>$currency_code,
                        "custom"=>$custom,
                        "amount"=>$amount,
                        //"return"=>$return,
                        "notify_url"=>$notify_url,
                        //"cancel_return"=>$cancel_return
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

                case "Coinpayments":

                    $url = "https://www.coinpayments.net/index.php";

                    $cmd = "_pay_simple";
                    $reset = "1";
                    $merchant = "d2a2ff1d7391af30262dee3353f43071";
                    $item_name = "$" . $amountOriginal . " Balance Top Up";
                    $item_desc = $description;
                    $currency = "USD";
                    $amountf = $amountToPay;
                    $want_shipping = "0";
                    $success_url = "https://receive-sms.com/success";
                    $cancel_url = "https://receive-sms.com/fail";
                    $ipn_url = "https://receive-sms.com/ipn/coinpayments";


                    $properties = array(
                        "cmd"=>$cmd,
                        "reset"=>$reset,
                        "merchant"=>$merchant,
                        "item_name"=>$item_name,
                        "item_desc"=>$item_desc,
                        "currency"=>$currency,
                        "amountf"=>$amountf,
                        "want_shipping"=>$want_shipping,
                        "success_url"=>$success_url,
                        "cancel_url"=>$cancel_url,
                        "ipn_url"=>$ipn_url,
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

        $paypalAccounts = paypalids::where('is_active', '=' ,true)->where('is_disposable', '=' ,false)->where('balance', '>=' ,0)->get();

        if (!$paypalAccounts){
            $paypalAccounts = paypalids::where('is_active', '=' ,true)->where('is_disposable', '=' ,false)->where('balance', '>=' ,0)->get();
        }
        $logs = paymentlog::where('status', '=' ,"Reversed")->where('created_at', '>', $earlier)->get();


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

        $this->notify("10", "30", "PayPal", "web_accept", "Completed", "buyer@gmail.com", "me@gmail.com", "20", $code="", "", "");
		
    }
	
	    public function valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            list($user, $domain ) = explode( '@', $email );
            if(checkdnsrr( $domain, 'mx')) {

            return true;
            };
        }else{
            return false;
        }
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
					$this->notify("0", "0", "Payza", $transactionStatus, "Payza", $buyerEmail, "", $payedAmount, $code, "","");
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

    public function blockio(){
        Log::info("good");

    }


    public function coinpayments(){


    $merchant_id = 'd2a2ff1d7391af30262dee3353f43071';
    $secret = '991580';

    if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
    die("No HMAC signature sent");
    }

    $merchant = isset($_POST['merchant']) ? $_POST['merchant']:'';
    if (empty($merchant)) {
    die("No Merchant ID passed");
    }

    if ($merchant != $merchant_id) {
    die("Invalid Merchant ID");
    }

    $request = file_get_contents('php://input');
    if ($request === FALSE || empty($request)) {
    die("Error reading POST data");
    }

    $hmac = hash_hmac("sha512", $request, $secret);
    if ($hmac != $_SERVER['HTTP_HMAC']) {
    die("HMAC signature does not match");
    }

    $ipn_type = $_POST['ipn_type'];
    $txn_id = $_POST['txn_id'];
    $item_desc = $_POST['item_desc'];
    $amount1 = floatval($_POST['amount1']);
    $currency1 = $_POST['currency1'];
    $status = intval($_POST['status']);
    $status_text = $_POST['status_text']; 
    $buyerEmail = $_POST['email'];


    $originalAmount = $this->getDescriptionVariables("originalAmount",$item_desc);
    $userEmail = $this->getDescriptionVariables("userEmail",$item_desc);

    Log::info("$originalAmount | $userEmail | $status");

    if ($status >= 100 || $status == 2) {
        
    Log::info("Successful payment");


        $originalAmount = $this->getDescriptionVariables("originalAmount",$item_desc);
        $userEmail = $this->getDescriptionVariables("userEmail",$item_desc);
        $code = $this->getDescriptionVariables("code",$item_desc);

        $payedAmount = $amount1; 
        $transactionType = "Payment";
        $transactionStatus = $status_text;

        $accountId = "d2a2ff1d7391af30262dee3353f43071";
        $paymentSystem = "coinpayments";
        $m_orderid  = $txn_id;
        // payment is complete or queued for nightly payout, success
        $this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem, $m_orderid);        
        $this->notify("0", "0", "coinpayments", "Payment", "coinpayments", $buyerEmail, "", $payedAmount, $code,"","");
        $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem, $m_orderid);

    } else if ($status < 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
    } else {
        //payment is pending, you can optionally add a note to the order page
    }
    die('IPN OK'); 

    }


    
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

            $transactionType = "Payemnt";
            $transactionStatus = $m_status;

            $buyerEmail = $userEmail;
            $accountId = $m_shop;
            $paymentSystem = "Payeer";           

			$checkLog = paymentlog::where('operation_id', $m_orderid)->first();
			if ($checkLog !== null) {
				Log::error("Payeer operation: $operation_id Already exist");
				return;
			}
			
            if ($m_sign == $sign_hash && $m_status == 'success'){

				$this->doTopup($userEmail,$payedAmount,$originalAmount,$code,$paymentSystem, $m_orderid);        
				$this->notify("0", "0", "Payeer", "Payment", "Payeer", $buyerEmail, "", $payedAmount, $code,"","");
            }
			
			$this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem, $m_orderid);

            return $m_orderid . "|" . $m_status;

        }

    }


    public function paypalIPNflat(){

     
           

            $payedAmount =  $_GET["payedAmount"];
            $originalAmount = $_GET["originalAmount"];
            $code = $_GET["code"];
            $transactionType =  $_GET["transactionType"];
            $transactionStatus =  $_GET["transactionStatus"];
            $userEmail =  $_GET["userEmail"];
            $buyerEmail = $_GET["buyerEmail"];
            $accountId =  $_GET["accountId"];
            $paymentSystem =  $_GET["paymentSystem"];
            $txn_id =  $_GET["txn_id"];
            $description =  $_GET["description"];
            
            $mc_fee =  $_GET["mc_fee"];
            $payment_status =  $_GET["payment_status"];
            $payment_type =  $_GET["payment_type"];
            $pending_reason =  $_GET["pending_reason"];

			
			$checkLog = paymentlog::where('operation_id', $txn_id)->first();
			if ($checkLog !== null) {
				Log::error("PayPal operation: $txn_id Already exist");
				return;
			}



			if ($description == "SMS-Verification"){
				$originalAmount = $payedAmount;
				$userEmail = $buyerEmail;
				$code = "SMS-Verification";
			}else{
			
				
				if ($description != "internal"){
				
				
					$originalAmount = $this->getDescriptionVariables("originalAmount",$description);
					$userEmail = $this->getDescriptionVariables("userEmail",$description);
					$code = $this->getDescriptionVariables("code",$description);
					
					if(!$this->valid_email($userEmail)) {
						Log::error("User email: $userEmail Not Valid");
						return;
					}
					
				}
				
					
					$toPaypalId = paypalids::where('email',$accountId)->first();
					$fromPaypalId =  paypalids::where('email',$buyerEmail)->first();


					if (!$fromPaypalId and $description != "SMS-Verification" and $description != "" and $description != "internal"){
						if (($payment_status == 'Completed') || ($payment_status == 'Pending')){
							// successful payment -> top up
                            if ($description != "internal" and $description != ""){
                                $this->doTopup($userEmail,$payedAmount,$originalAmount,$code, "PayPal", $txn_id);
                            }

				 
						}
					}


			}
			


            // loging the event
			$amountNoFee = $payedAmount;
            if ($payedAmount > 0){
                $payedAmount = $payedAmount - $mc_fee;
            }

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, "PayPal",$txn_id);

			// Update balance

				$oldBalance = $newBalance = $senderOldBalance = $senderNewBalance = "";

				if ($transactionStatus == "Completed" or $transactionStatus == "Reversed" or $transactionStatus == "Canceled_Reversal"){
					$oldBalance = $toPaypalId['balance'];
					$newBalance = $oldBalance + $payedAmount;
					paypalids::where('email', "=", $accountId)->update(['balance' => $newBalance]);
                    
                    $oldTotal = $toPaypalId['total'];
					$newTotal = $oldTotal + $payedAmount;
                    paypalids::where('email', "=", $accountId)->update(['total' => $newTotal]);
                    

					if ($fromPaypalId){
						$senderOldBalance = $fromPaypalId['balance'];
						$senderNewBalance = $senderOldBalance - $amountNoFee;
						paypalids::where('email', "=", $buyerEmail)->update(['balance' => $senderNewBalance]);
					}
					
				}
	
			// notify
			$this->notify($oldBalance, $newBalance, "PayPal", $transactionType, $transactionStatus, $buyerEmail, $accountId, $payedAmount, $code, $senderOldBalance, $senderNewBalance);

        
    }
    public function paypalIPN(){

        $ipn = new PaypalIPN();
        Log::info("inside ipn");
        

        $verified = $ipn->verifyIPN();
     

        if ($verified) {
            Log::info("verified");
            Log::info($_POST["txn_type"]);
           return;

			$payedAmount = $originalAmount = $code = $transactionType = $transactionStatus = $userEmail = $buyerEmail = $accountId = $paymentSystem = $txn_id = "";
			
            if (isset($_POST["custom"])){$description = $_POST["custom"];}else{$description = "";}


            if (isset($_POST["mc_fee"])){$mc_fee = $_POST["mc_fee"];}else{$mc_fee = "0";}
            if (isset($_POST["mc_gross"])){$payedAmount = $_POST["mc_gross"];}else{$payedAmount = "";}
            if (isset($_POST["txn_type"])){$transactionType = $_POST["txn_type"];}else{$transactionType = "";}
            if (isset($_POST["payment_status"])){$transactionStatus = $_POST["payment_status"];}else{$transactionStatus = "";}
            if (isset($_POST["payer_email"])){$buyerEmail = $_POST["payer_email"];}else{$buyerEmail = "";}
            if (isset($_POST["business"])){$accountId = $_POST["business"];}else{$accountId = "";}
            if (isset($_POST["payment_status"])){$payment_status = $_POST["payment_status"];}else{$payment_status = "";}
            if (isset($_POST["payment_type"])){$payment_type = $_POST["payment_type"];}else{$payment_type = "";}
            if (isset($_POST["pending_reason"])){$pending_reason = $_POST["pending_reason"];}else{$pending_reason = "";}
			
            if (isset($_POST["txn_id"])){$txn_id = $_POST["txn_id"];}else{$txn_id = null;}
			
			$checkLog = paymentlog::where('operation_id', $txn_id)->first();
			if ($checkLog !== null) {
				Log::error("PayPal operation: $txn_id Already exist");
				return;
			}



			if ($description == "SMS-Verification"){
				$originalAmount = $payedAmount;
				$userEmail = $buyerEmail;
				$code = "SMS-Verification";
			}else{
			
				
				if ($description != "internal"){
				
				
					$originalAmount = $this->getDescriptionVariables("originalAmount",$description);
					$userEmail = $this->getDescriptionVariables("userEmail",$description);
					$code = $this->getDescriptionVariables("code",$description);
					
					if(!$this->valid_email($userEmail)) {
						Log::error("User email: $userEmail Not Valid");
						return;
					}
					
				}
				
					
					$toPaypalId = paypalids::where('email',$accountId)->first();
					$fromPaypalId =  paypalids::where('email',$buyerEmail)->first();


					if (!$fromPaypalId and $description != "SMS-Verification" and $description != "" and $description != "internal"){
						if (($payment_status == 'Completed') || ($payment_status == 'Pending')){
							// successful payment -> top up
                            if ($description != "internal" and $description != ""){
                                $this->doTopup($userEmail,$payedAmount,$originalAmount,$code, "PayPal", $txn_id);
                            }

				 
						}
					}


			}
			


            // loging the event
			$amountNoFee = $payedAmount;
            if ($payedAmount > 0){
                $payedAmount = $payedAmount - $mc_fee;
            }

            $this->log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, "PayPal",$txn_id);

			// Update balance

				$oldBalance = $newBalance = $senderOldBalance = $senderNewBalance = "";

				if ($transactionStatus == "Completed" or $transactionStatus == "Reversed" or $transactionStatus == "Canceled_Reversal"){
					$oldBalance = $toPaypalId['balance'];
					$newBalance = $oldBalance + $payedAmount;
					paypalids::where('email', "=", $accountId)->update(['balance' => $newBalance]);
                    
                    $oldTotal = $toPaypalId['total'];
					$newTotal = $oldTotal + $payedAmount;
                    paypalids::where('email', "=", $accountId)->update(['total' => $newTotal]);
                    

					if ($fromPaypalId){
						$senderOldBalance = $fromPaypalId['balance'];
						$senderNewBalance = $senderOldBalance - $amountNoFee;
						paypalids::where('email', "=", $buyerEmail)->update(['balance' => $senderNewBalance]);
					}
					
				}
	
			// notify
			$this->notify($oldBalance, $newBalance, "PayPal", $transactionType, $transactionStatus, $buyerEmail, $accountId, $payedAmount, $code, $senderOldBalance, $senderNewBalance);


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

    public function getnumbers(){
        $numbers = number::where('is_private',true)->where('email',$email)->get();
        
    }

    public function getPrice($amount=1,$period=1, $email=null){

        if ($email == null){
            if (Auth::check()){
                $email = Auth::user()->email;
                $numbers = number::where('is_private',true)->where('email',$email)->get();

                if (!is_numeric($amount)){$amount=1;}

                $totalAmount = $amount + count($numbers);
            }else{
                $totalAmount = $amount;
            }
        }else{
            $numbers = number::where('is_private',true)->where('email',$email)->get();
            if (!is_numeric($amount)){$amount=1;}
            $totalAmount = $amount + count($numbers);
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
        if (is_numeric($amount) and is_numeric($period)){
            $price = $this->getPrice($amount,$period);
            if (Auth::check()){
                if($price > Auth::user()->balance){$isPossible = false;}else{$isPossible = true;}
            }else{
                $isPossible = false;
            }
        }else{
            $isPossible = false;
            $price = "n/a";

        }


        header('Content-type: application/json');
        return json_encode(array ('isPossible'=> $isPossible,'price'=>$price));
    }

    private function doTopup($email,$payedAmount,$originalAmount,$code,$paymentSystem, $operation_id = null){


        if(is_numeric($payedAmount) && is_numeric($originalAmount) && is_string($paymentSystem) && is_string($email) ){
			if ($operation_id !== null){
				$log = paymentlog::where('operation_id', $operation_id)->first();
				if ($log !== null) {
					Log::error("operation: $operation_id Already exist");
					return;
				}
				
			}
   
            // check coupon
            $topup  = $originalAmount;
            $couponApplied = $this->CouponToPrice($originalAmount,$paymentSystem,$code);
            if ($couponApplied <> $payedAmount) {$topup = $payedAmount ;}
            Log::info("[Payment Successful] $topup | $email | $paymentSystem");
            // topup
            $user = User::where('email', $email)->first();
            if ($user){
                User::where('email', "=", $email)->update(['paid' => $topup]);

            $topup  = $topup + $user['balance'];

            
            // send receipt

            $data['name'] = $user['name'];
            $data['date'] = Carbon::now();
            $data['amount'] = $originalAmount;
            $data['finalBalance'] = $topup;
            $data['type'] = $paymentSystem;

            try {
                Mail::to($email)->send(new topupReceipt($data));
            }catch(Exception $e){
                Log::error("error sending to $email");

            }
			
			User::where('email', "=", $email)->update(['balance' => $topup]);
            
            }else{
                Log::error("no user with email $email");
            }
            }else{
            Log::error("[$email] [$payedAmount] [$originalAmount] [$code] [$paymentSystem] something is not right");
        }
        return;
       
    }

	
    private function notify($oldBalance = "", $newBalance = "", $system = "PayPal", $type = "web_accept", $status = "Completed", $from = "buyer@gmail.com", $to = "me@gmail.com", $amount = "20", $code="Internal", $senderOldBalance="", $senderNewBalance=""){
		
		$content = "";
		
		if ($code <> ""){
			$content = $content . "Code: $code". PHP_EOL;
		}

		
		if ($system == "PayPal"){
			$content = $content . "Receiver:" . PHP_EOL . "$to" . PHP_EOL;
			if ($oldBalance <> "" and $newBalance <> ""){$content = $content . "$$oldBalance -> $$newBalance" . PHP_EOL;}

			$content = $content . "Sender:" . PHP_EOL . "$from" . PHP_EOL;
			
			if ($senderOldBalance <> "" and $senderNewBalance <> ""){$content = $content . "$$senderOldBalance -> $$senderNewBalance" . PHP_EOL;}

		}else{
			$content = $content . "Sender: $from";
		}


		$title = "$system: ";
		
		if ($amount <> "" and $amount <> 0){
			$title =  $title . "$$amount";
		}
		
		
		if ($type == "" or $type == null){
			$title = $title . " $status transaction";
		}else{
			$title = $title . " $type";
		}
        $Simplepush = new Simplepush;
        $Simplepush->send("W6T4J9", $title, $content, $status);

    }


    Private function log($payedAmount, $originalAmount, $code, $transactionType, $transactionStatus, $userEmail, $buyerEmail, $accountId, $paymentSystem, $operationId = null){


        if ($payedAmount == ''){$payedAmount = 0;}
        if ($originalAmount == ''){$originalAmount = 0;}


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
                "operation_id"=>$operationId,
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
