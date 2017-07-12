<?php

namespace App\Http\Controllers;

use App\subscriber;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{

    public function confirm($email)
    {
        if( ! $email)
        {
            flash()->overlay('Invalid or nonexistent e-mail.', 'Subscribe Confirmation');
            return redirect('/');
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

        return redirect('/');
    }

    Public function subscribe(Request $request){

        $subscribed = subscriber::where('email', $request->email)->where('subscribed', true)->first();

       if(!is_null($subscribed)) {
            $message = "You are already subscribed.";
            return view('message')->with('title', 'Error!')->with('content',$message)->with('titleClass','text-danger');

        }else{
           $subscribed = subscriber::where('email', $request->email)->first();
           if ($subscribed ){
               $subscribed->subscribed = true;
               $subscribed->save();
           }else{
               $subscriber = new subscriber();
               $subscriber->email = $request->email;
               $subscriber->save();
           }

           Mail::to($request->email)->send(new subscribeConfirmation($request->email));
           flash()->overlay('You have been subscribed successfully. Please check your email for confirmation', 'Thank you for your subscription!');

           return redirect('/');


       }


        }
}
