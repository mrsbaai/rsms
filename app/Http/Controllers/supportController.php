<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use App\contact;
use App\Mail\contactReceived;
use Validator;


class supportController extends Controller
{
    //
    public function send(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'message'     => 'required|max:600|min:20',
            'subject'     => 'required|max:255|min:10',
			'g-recaptcha-response' => 'recaptcha',
		]);

		if ($validator->fails()) {
			$errors = $validator->errors();
			return view('support')->with('result', '- Error!')->with('errors', $errors);
		}

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
			
			
			$sup = contact::where("subject", $subject)->where("message", $content)->where("email", $email)->orderBy('id', 'desc')->first();
			$title = $subject;
			$url = 'https://receive-sms.com/fast/support/' . $sup['id'];
			$status = "[SUPPORT]";
			$Simplepush = new Simplepush;
			$Simplepush->send("W6T4J9", $title, $url, $status);
			//PushBullet::all()->link($subject, 'https://receive-sms.com/fast/support/' . $sup['id'], $content);
            Mail::to($email)->send(new contactReceived());

            $subject = "[SUPPORT] " . $subject;
            $to = 'support@receive-sms.com';
            Mail::send('emails.contact', ['content' => $content], function ($message) use($subject,$email,$name, $to){
                $message->from($email, $name);
                $message->subject($subject);
                $message->to($to);
            });

        return view('support')->with('result', '<br/><br/>Sent! Please check your e-mail for confirmation. <br/><br/><span style="color:red;">IMPORTANT:</span> If you don\'t find the confirmation email in your inbox, please see check <span style="color:red;">SPAM FOLDER</span>, and mark as not spam.');


    }


}
