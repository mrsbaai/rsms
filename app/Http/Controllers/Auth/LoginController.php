<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/inbox';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	 
	protected function redirectTo()
    {
		if (Auth::user()->balance == 0){
			return redirect('/topup');
			
		}else{
			return redirect('/inbox');
		}
    }
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
