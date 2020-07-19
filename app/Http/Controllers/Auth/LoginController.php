<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

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

    }
	
	protected function credentials(\Illuminate\Http\Request $request)
    {
        //return $request->only($this->username(), 'password');
        //User::where('email', "=", $request->{$this->username()})->update(['agent' => $request->server('HTTP_USER_AGENT')]);
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'is_active' => 1];
    }
	
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
