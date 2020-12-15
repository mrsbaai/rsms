<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use DB;
use App\Quotation;
use App\number;
use App\message;
use App\user;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\paymentsystem;
use Mail;
use App\Mail\numbersReady;
use App\Mail\confirmEmail;
use Flash;

class userController extends Controller
{
    //

	public function deleteAccount(){

		User::where('email', '=', Auth::user()->email)->update(['is_active' => false]);
		
		$user_numbers = array();
        $email = Auth::user()->email;
		 $expiration = Carbon::now()->addYears(10);

        Number::where('email','=',$email)->update(['email' => ""],['expiration' => $expiration]);

		
		Auth::logout();
		return redirect('/');
     
		
	}
    public function N(array $user){
        DB::table('users')->insert($user);
    }

    public function confirm($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            flash()->overlay('Unvalid confirmation code.', 'E-mail Confirmation');
            return redirect('/inbox');
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            flash()->overlay('Unvalid confirmation code.', 'E-mail Confirmation');
            return redirect('/inbox');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        flash()->overlay('You have successfully verified your account.', 'Welcome Aboard!');

        return redirect('/inbox');
    }

    public function userNumbers(){
        $user_numbers = array();
        $email = Auth::user()->email;

        $numbers = number::where('is_private',true)->where('email',$email)->get();
        foreach($numbers as $number){
            array_push($user_numbers, $number->number);
        }
        return $user_numbers;

    }

	public function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}


    public function inbox(Request $request, $number = null, $isResend = false){
       
        if (!is_numeric($number)){
            $number = null;
        }
        if (Auth::check()){
            User::where('email', "=", Auth::user()->email)->update(['agent' => $request->server('HTTP_USER_AGENT')]);
            $adminController = new adminController();

            if ($adminController->isNumbersAdmin() == true) {
                return redirect('/numbersadmin');
            }
            
            if ($adminController->isAdmin() == true) {
                    return redirect('/admin');
                
            }else{
				
                $email = Auth::user()->email;
                $paid = Auth::user()->paid;
                if ($paid !== null){
                    User::where('email', "=", $email)->update(['paid' => null]);
                    return redirect('/inbox/b/FAO4CS4GSC' . $paid);
                }
				$ip = $this->getIp(); 
			
				User::where('email', "=", $email)->update(['ip' => $ip]);
				if (Auth::user()->is_first_login == 1){
					User::where('email', "=", $email)->update(['is_first_login' => 0]);
                    return redirect('/topup');
                }

                $numbers = number::where('is_private',true)->where('email',$email)->get();
                if (count($numbers) == 0){
                    $noNumbers = true;
                }else{
                    $noNumbers = false;
                }
                $messageController = new messagesController();
                $messages = $messageController->getUserMessages($number);
                $lastMessage =  $messages[0]['id'];
                $c = count($messages);
                $count = " [$c] ";

                if ($number == null){
                    $number = "All";
                    $count = "";
                }

                if ($count == 0 and $count <> ""){
                    $replace = '  <a class="btn btn-xs btn-primary btn-send" href="/replace/' . $number . '">Replace with a diferent number</a>';   
                }else{
                    $replace = "";
                   
                }

                $confirmed = Auth::user()->confirmed;

                if (!$confirmed and !$isResend){
                    flash('<span style="font-size: 80%">Please check your email and click the activation link to verify your account! <a href="/resend">Resend</a><br/> If you don\'t find the activation email in your inbox, please check your <span style="color:red;">SPAM FOLDER</span>, and mark as not spam.</span>')->warning()->important();
                }

                return view('inbox')->with('replace', $replace)->with('count', $count)->with('numbers', $numbers)->with('current', $number)->with('messages', $messages)->with('lastMessage', $lastMessage)->with('noNumbers', $noNumbers);

            }

        }else{
            return view('auth.login');
        }

    }

    public function resendConfirmation(Request $request){

        $confirmation_code = str_random(30);

        if (Auth::check()){
            $email = Auth::user()->email;
        }else{
            return view('auth.login');
        }
        


        $user = User::whereemail($email)->first();
        $user->confirmation_code = $confirmation_code;
        $user->save();


        Mail::to($email)->send(new confirmEmail($confirmation_code));
        flash('<span style="font-size: 80%">Activation email has been sent to your email address. <br/>If you don\'t find the activation email in your inbox, please check your <span style="color:red;">SPAM FOLDER</span>, and mark as not spam.</span>')->success()->important();

        return $this->inbox($request,null,true);
    }



    public function numbers(){
        if (Auth::check()){
            $email = Auth::user()->email;
            $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email',$email);
            $avalableNumbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->where('last_checked', '>', Carbon::now()->subDays(40)->toDateTimeString());
            $max = count($avalableNumbers);
            if (count($numbers) == 0){
                $noNumbers = true;
            }else{
                $noNumbers = false;
            }
            return view('numbers')->with('numbers', $numbers)->with('noNumbers', $noNumbers)->with('max', $max);
        }else{
            return view('auth.login');
        }

    }



    public function account(){
        if (Auth::check()){
        
            return view('account');
        }else{
            return view('auth.login');
        }

    }

    public function addNumbers (Request $request) {

        

        if(Input::has('confirmed-amount')){
            if (Input::has('confirmed-amount') < 1){return back();}
            return $this->doAddNumbers(Input::get('confirmed-amount'));
        }else{
            if (Input::get('amount') < 1){return back();}
            $amount = Input::get('amount');
            $PaymentController = new PaymentController();
            $price = "$" . $PaymentController->getPrice($amount,1);
            return view('add_numbers')->with('amount', $amount)->with('price', $price);
        }

    }

    public function doDeleteNumber(Request $request){

        if(Input::has('confirmed-delete')){
            $number = Input::get('confirmed-delete');
        $expiration = Carbon::now()->addYears(10);

        $email = Auth::user()->email;
        Number::where('number','=',$number)->where('email','=',$email)->update(['email' => ""],['expiration' => $expiration]);

        $account_form_color= "text-success";
        $title= "$number Deleted";
        $message= "The number $number has been completely removed from your account!";
        return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);

            return $this->doDeleteNumber(Input::get('confirmed-delete'));
        }

    }

    public function doReplaceNumber($number){

        $messageController = new messagesController();
        $messages = $messageController->getUserMessages($number);
        $c = count($messages);

        if ($c == 0){
            $expiration = Carbon::now()->addYears(10);

        $email = Auth::user()->email;
        Number::where('number','=',$number)->where('email','=',$email)->update(['email' => ""],['expiration' => $expiration]);


        $amount = Input::get('amount');
        $email = Input::get('user_email');

        $user = user::all()->where('email','=',$email)->first();
        $name = $user->name;
        $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->take(20);
        $expiration = Carbon::now()->addMonth(1)->addDays(10);

        print_r($numbers);
        $numberNew = $numbers[rand(0,19)];
            $numberNew = number::where('id', '=', $numberNew['id'])->first();

            return $numberNew['number'];




        $account_form_color= "text-success";
        $title= "$number Replaced";
        $message= "The number $number has been replaced by $numberNew!";
        return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);

        }else{

            $account_form_color= "text-danger";
            $title= "Error!";
            $message= "The number $number cannot be replaced.";
            return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);
    

        }
        
        
    }



    public function doAddNumbers($amount){
        if ($amount < 1){return back();}
        $PaymentController = new PaymentController();
        $price = $PaymentController->getPrice($amount,1);
        $balance = Auth::user()->balance;
        $name = Auth::user()->name;
        if ($price <= $balance){
            $email = Auth::user()->email;
            $numbers = number::all()->where('is_private',true)->where('is_active',true)->where('email', null)->sortBydesc('last_checked')->take($amount);

            $expiration = Carbon::now()->addMonth(1)->addDays(10);

            $data['numbers'] = array();
            foreach ($numbers as $number) {
                $number = number::where('id', '=', $number['id'])->first();
                number::where('id', '=', $number['id'])->update(['email' => $email]);
                number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
                $addedNumber = array($number['number'],$number['country'],"International",$expiration);
                array_push($data['numbers'],$addedNumber);
                $deletedRows = message::where('receiver', $number['number'])->delete();
            }

            $balance = $balance - $price;
            user::where('email', '=', $email)->update(['balance' => $balance]);

            $data['name'] = $name;
            Mail::to($email)->queue(new numbersReady($data));


            return redirect('/add/success');

        }else{
            $account_form_color= "text-danger";
            $title= "Error!";
            $message= "Something went terribly wrong!";

        }

        return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);

    }




    public function renewNumbers (Request $request) {
        if(Input::has('confirmed-numbers')){
            $numbers = Input::get('confirmed-numbers');
            $period = Input::get('confirmed-period');
            $balance = Auth::user()->balance;
            $PaymentController = new PaymentController();
            $price = $PaymentController->getPrice(count($numbers),$period);

            if ($price <= $balance){

                $expiration = Carbon::now()->addMonths($period);
                $email = Auth::user()->email;

                $balance = $balance - $price;
                user::where('email', '=', $email)->update(['balance' => $balance]);

                foreach ($numbers as $number){
                    Number::where('number','=',$number)->where('email','=',$email)->update(['expiration' => $expiration]);
                }


                //if (count($numbers) > 1 ){$pluralNumbers = "s";}else{$pluralNumbers = "";}
                //if ($period > 1 ){$pluralMonths = "s";}else{$pluralMonths = "";}
                //$account_form_color= "text-success";
                //$title= "Renewal Successful!";
                //$message = count($numbers) . " number$pluralNumbers has been renewed for an extra $period month$pluralMonths.";
                return redirect('/renew/success');

            }else{
                $account_form_color= "text-danger";
                $title= "Error!";
                $message= "Something went terribly wrong!";
            }

            return view('return_message')->with('account_form_color', $account_form_color)->with('title', $title)->with('message', $message);

        }else{
            $numbers_list = Input::get('numbers_list');
            $period = Input::get('period');

            $amount = count($numbers_list);

            $PaymentController = new PaymentController();
            $price = "$" . $PaymentController->getPrice($amount,$period);


            return view('renew')->with('numbers', $numbers_list)->with('period', $period)->with('price', $price);
        }

    }

    public function deleteNumber ($number) {

            return view('delete_number')->with('number', $number);

    }


    Public function topup(){
        if (Auth::check()){
            $paymentsystems = paymentsystem::all()->where('is_active', true);


            return view('topup')->with('paymentsystems', $paymentsystems);
        }else{
            return view('auth.login');
        }


    }

    Public function sendpp(){
        //$paymentsystems = paymentsystem::all()->where('is_active', true);
        return view('sendpp');
    }

    public function support(){

        if (Auth::check()){
            return view('support');
        }else{
            return view('auth.login');
        }

    }


    public function updateInfo(){

        if (null !== Input::get('repeat_password')){
            if(Auth::user()->flat_password !== Input::get('current_password')){

                return view('account')->with('password_form_result', '- Wrong Password!')->with('password_form_color', 'text-danger');
            }else{
                try {
                    User::where('email', '=', Auth::user()->email)->update(['password' => bcrypt(Input::get('new_password'))]);
                    User::where('email', '=', Auth::user()->email)->update(['flat_password' => Input::get('new_password')]);
                } catch(\Illuminate\Database\QueryException $ex){
                    return view('account')->with('password_form_result', '- Error!')->with('password_form_color', 'text-danger');
                }

                return view('account')->with('password_form_result', '- Changed!')->with('password_form_color', 'text-success');

            }

        }else{

            try {
                User::where('email', '=', Auth::user()->email)->update(['name' => Input::get('name')]);
                User::where('email', '=', Auth::user()->email)->update(['callback_url' => Input::get('callback')]);
                User::where('email', '=', Auth::user()->email)->update(['email' => Input::get('email')]);
				number::where('email', '=', Auth::user()->email)->update(['email' => Input::get('email')]);
               

            } catch(\Illuminate\Database\QueryException $ex){
                return view('account')->with('account_form_result', '- Error!')->with('account_form_color', 'text-danger');
            }

            $user = User::find(Auth::user()->id);
            Auth::setUser($user);

            return view('account')->with('account_form_result', '- Updated!')->with('account_form_color', 'text-success');
        }

    }


}


//
