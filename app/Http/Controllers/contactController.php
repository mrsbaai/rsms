<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use App\contact;
use App\Mail\contactReceived;
use App\Http\Requests\ContactFormRequest;

class contactController extends Controller
{
    //
    public function send(Request $request)
    {


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


            //$subject = "(Receive-SMS Contact Form) " . $subject;
            //$to = 'support@receive-sms.com';
            //Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name, $to){
                //$message->from($email, $name);
                //$message->subject($subject);
                //$message->to($to);
            //});

            //Mail::to($email)->send(new contactReceived());


            return view('contact')->with('result', '- Sent!');


    }


}
