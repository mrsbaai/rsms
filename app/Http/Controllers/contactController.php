<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use App\contact;
use App\Mail\contactReceived;
use Validator;

class contactController extends Controller
{
    //
    public function store(Request $request)
    {

		$validator = Validator::make($request->all(), [
			'g-recaptcha-response' => 'required|recaptcha',
            'name'    => 'required|max:50|min:5',
            'email'   => 'required|email|max:70|min:9',
            'message'     => 'required|max:600|min:15',
            'subject'     => 'required|max:255|min:10'
		]);

		if ($validator->fails()) {
			return view('contact')->with('result', '- Error With The Form!');
		}


            $name = $request->get('name');
            $email =  $request->get('email');


            $subject =  $request->get('subject');
            $content = $request->get('message');


            // save contact message here

            $contact = new contact();
            $contact->is_support = false;
            $contact->message = $content;
            $contact->subject = $subject;
            $contact->user_id = null;
            $contact->email = $email;
            $contact->name = $name;
            $contact->is_responded = false;
            $contact->save();


            $subject = "[CONTACT] " . $subject;
            $to = 'support@receive-sms.com';
            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name, $to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });

            Mail::to($email)->send(new contactReceived());


            return view('contact')->with('result', '- Sent!');


    }


}
