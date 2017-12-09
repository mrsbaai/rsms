<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use App\contact;
use App\Mail\contactReceived;

class supportController extends Controller
{
    //
    public function send()
    {



            $name = Auth::user()->name;
            $email =  Auth::user()->email;
            $user_id = Auth::user()->id;

            $subject =  Input::get('subject');
            $content = Input::get('message');
            // save support message here

            $contact = new contact();
            $contact->is_support = true;
            $contact->message = $content;
            $contact->subject = $subject;
            $contact->user_id = $user_id;
            $contact->email = $email;
            $contact->name = $name;
            $contact->is_responded = false;
            $contact->save();

            Mail::to($email)->send(new contactReceived());

            $subject = "(Receive-SMS Support From): " . $subject;
            $to = 'support@receive-sms.com';
            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name, $to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });

        return view('support')->with('result', '- Sent!');


    }


}
