<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use App\contact;
use App\Mail\contactReceived;

class contactController extends Controller
{
    //
    public function send()
    {


            $name = Input::get('name');
            $email =  Input::get('email');


            $subject =  Input::get('subject');
            $content =  Input::get('message');


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


            $to = 'support@receive-sms.com';
            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name,$to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });

            Mail::to($email)->send(new contactReceived());

            //Mail::to("support@receive-sms.com")->from($email)->subject($subject)->text($content);

            return view('contact')->with('result', '- Sent!');


    }


}
