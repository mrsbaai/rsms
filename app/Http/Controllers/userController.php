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



    public function inbox($number = null){
        if (!is_numeric($number)){
            $number = null;
        }
        if (Auth::check()){
            $adminController = new adminController();
            if ($adminController->isAdmin() == true) {
                return redirect('/admin');
            }else{
                $email = Auth::user()->email;
                $paid = Auth::user()->paid;
                if ($paid !== null){
                    User::where('email', "=", $email)->update(['paid' => null]);
                    return redirect('/inbox/b/FAO4CS4GSC' . $paid);
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

                if ($number == null){$number = "All";}
                $confirmed = Auth::user()->confirmed;


                if (!$confirmed){
                    flash('<span style="font-size: 80%">Please check your email and click the activation link to verify your account! <a href="/resend">Resend</a></span>')->warning()->important();
                }
                return view('inbox')->with('numbers', $numbers)->with('current', $number)->with('messages', $messages)->with('lastMessage', $lastMessage)->with('noNumbers', $noNumbers);

            }

        }else{
            return view('auth.login');
        }

    }

    public function resendConfirmation(){

        // build this --->
        $confirmation_code = str_random(30);
        $email = Auth::user()->email;

        $user = User::whereemail($email)->first();
        $user->confirmation_code = $confirmation_code;
        $user->save();


        Mail::to($email)->send(new confirmEmail($confirmation_code));
        flash()->overlay('Confirmation email has been sent to your email address.', 'confirmation sent!');
        return redirect('/inbox');
    }



    public function numbers(){
        if (Auth::check()){
            $email = Auth::user()->email;
            $numbers = number::all()->where('is_private',true)->where('email',$email);
            $avalableNumbers = number::all()->where('is_private',true)->where('email', null);
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
            return $this->doAddNumbers(Input::get('confirmed-amount'));
        }else{
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



    public function doAddNumbers($amount){

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
