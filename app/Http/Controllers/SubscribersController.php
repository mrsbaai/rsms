<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\subscriber;
use App\suppression;
use Illuminate\Http\Request;
use Mail;
use App\Mail\subscribeConfirmation;
use App\Http\Controllers\MG_Email;
use Log;

class SubscribersController extends Controller
{

    public function confirm($email)
    {
        if( ! $email)
        {
            flash()->overlay('Invalid or nonexistent e-mail.', 'Subscribe Confirmation');
            return redirect('/home');
        }

        $subscriber = subscriber::whereemail($email)->first();

        if ( ! $subscriber)
        {
            flash()->overlay('Invalid or nonexistent e-mail.', 'Subscribe Confirmation');
            return redirect('/');
        }

        $subscriber->confirmed = 1;
        $subscriber->save();

        flash()->overlay('Subscription verified successfully', 'Thank you!');

        return redirect('/home');
    }

    public function unsubscribe(Request $request){

        $email= Input::get('email');

        $suppressed = suppression::where('email', $email)->first();

        if(is_null($suppressed)) {
            $suppression = new suppression();
            $suppression->email = $email;
            $suppression->save();
        }

        $subscriber = subscriber::where('email', $email)->first();
        if ($subscriber ){
            $subscriber->subscribed = false;
            $subscriber->save();
        }
        flash()->overlay(' You have successfully unsubscribed from ' . config('app.name') . ' Newsletter. You will no longer receive new notifications and special offers.', 'You have been successfully unsubscribed');
        return redirect('/home');


    }
    public function valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            list($user, $domain ) = explode( '@', $email );
            if(checkdnsrr( $domain, 'mx')) {

            return true;
            };
        }else{
            return false;
        }
    }

    public function showUnsubscribe($email = ""){

        return view('unsubscribe')->with("email",$email);
    }

    Public function subscribe(Request $request){

        if(!$this->valid_email($request->email)) {
            flash()->overlay($request->email . ' Is not a valid email address.', 'Invalid E-mail!');
            return redirect('/home');
        }


        $suppression = suppression::where('email', $request->email)->first();

        if(!is_null($suppression)) {
            $suppression->delete();
        }


        $subscribed = subscriber::where('email', $request->email)->where('subscribed', true)->first();



       if(!is_null($subscribed)) {
           flash()->overlay('Your e-mail already exists in our database.', 'Already subscribed!');
           return redirect('/home');

        }else{
           if(isset($_COOKIE['origin_ref'])){
               $source = $_COOKIE['origin_ref'];
           }else{
               $source = null;
           }

           $subscribed = subscriber::where('email', $request->email)->first();

           if ($subscribed ){
               $subscribed->subscribed = true;
               $subscribed->save();
           }else{
               $subscriber = new subscriber();
               $subscriber->email = $request->email;
               $subscriber->source = mb_strimwidth($source, 0, 190);
               $subscriber->save();
           }

           Mail::to($request->email)
           ->send(new subscribeConfirmation($request->email));
           flash()->overlay('Thank you for your submission! A confirmation email has been sent. <br/><br/>IMPORTANT! If you don\'t find the confirmation email in your inbox, please check your >>>SPAM FOLDER<<<, and MARK AS NOT SPAM.', 'Please CONFIRM Your Subscription.');

           return redirect('/subscribed');


       }


        }
}
