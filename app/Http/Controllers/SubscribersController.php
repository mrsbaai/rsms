<?php

namespace App\Http\Controllers;

use App\subscriber;
use Illuminate\Http\Request;


class SubscribersController extends Controller
{
    //
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


            $message = "Thank you for your subscription.";
            return view('message')->with('title', 'Subscribed Successfully!')->with('content',$message)->with('titleClass','text-success');

        }


        }
}
