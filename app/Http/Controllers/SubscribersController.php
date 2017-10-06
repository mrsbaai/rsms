<?php

namespace App\Http\Controllers;

use App\subscriber;
use App\suppression;
use Illuminate\Http\Request;
use Mail;
use App\Mail\subscribeConfirmation;

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

        $suppressed = suppression::where('email', $request->email)->first();

        if(is_null($suppressed)) {
            $suppression = new suppression();
            $suppression->email = $request->email;
            $suppression->save();
        }

        $subscriber = subscriber::where('email', $request->email)->first();
        if ($subscriber ){
            $subscriber->subscribed = false;
            $subscriber->save();
        }
        flash()->overlay(' You have successfully unsubscribed from ' . config('app.name') . ' Newsletter. You will no longer receive new demo numbers notifications and special offers.', 'You have been successfully unsubscribed');
        return redirect('/home');


    }
    Public function subscribe(Request $request){
        if ($request->email == null){return redirect('/home');}
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
               $subscriber->source = $source;
               $subscriber->save();
           }

           Mail::to($request->email)->send(new subscribeConfirmation($request->email));
           flash()->overlay('You have been subscribed successfully. Please check your e-mail for confirmation.', 'Thank you for your subscription!');

           return redirect('/home');


       }


        }
}
