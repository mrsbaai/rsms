<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
class contactController extends Controller
{
    //
    public function send()
    {


        if (null !== Input::get('name')){
            $name = Input::get('name');
            $email =  Input::get('email');
            $to = 'contact@receive-sms.com';

            $subject =  Input::get('subject');
            $content = "From: " . $email . "<br/><br/>" . Input::get('message');


            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name,$to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });
            return view('contact')->with('result', '- Sent!');

        }else{
            $name = Auth::user()->name;
            $email =  Auth::user()->email;
            $to = 'support@receive-sms.com';
            $subject =  Input::get('subject');
            $content = "From: " . $email . "<br/><br/>" . Input::get('message');


            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name,$to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });
            return view('support')->with('result', '- Sent!');

        }




    }


}
