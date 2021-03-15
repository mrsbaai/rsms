<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use App\Mail\confirmEmail;
use App\Mail\newCoupon;
use Flash;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/inbox/welcome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    public function valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($user, $domain ) = explode( '@', $email );
            return checkdnsrr( $domain, 'mx');
        }else{
            return false;
        }
    }

    protected function create(array $data)
    {


        $confirmation_code = str_random(30);


        if ($this->valid_email($data['email'])){
            Mail::to($data['email'])->send(new confirmEmail($confirmation_code));
            $when = Carbon::now();
            $email = $data['email'];
            $data1['subj'] = "GET 10% Off - Cryptocurrency Promotional Code";
            $data1['header'] = "Welcome to [Receive-SMS], Topup your account using cryptocurrency and get 10% off!";
            $data1['coupon'] = "BITCOIN-FOREVER-578";
            $data1['date'] = Carbon::now()->addDays(7);
            $data1['email'] = $email;          
            Mail::to($email)->later($when, new newCoupon($data1));
        }

        flash()->overlay('Confirmation email has been sent to your email address. Please check your e-mail for confirmation. <br/>IMPORTANT! If you don\'t find the confirmation email in your inbox, please see your SPAM FOLDER, and check as Not Spam.', 'Thanks for signing up!');

        if(isset($_COOKIE['origin_ref'])){
            $source = $_COOKIE['origin_ref'];
        }else{
            $source = null;
        }

        flash()->overlay('Machakil', 'la waaalo!');


        return false;
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'flat_password' => $data['password'],
            'confirmation_code' => $confirmation_code,
            'source' => mb_strimwidth($source, 0, 190),
            "created_at"=>Carbon::now()
        ]);



    }

}
