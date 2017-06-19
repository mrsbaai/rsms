<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use App\number;
use App\message;

class apiController extends Controller
{
    Public function show($email=null,$password=null,$number = null){

        try {
            $user = User::where('email', '=', $email)->first();
            if ($user['flat_password'] == $password){
                if ($number){
                    $messages = $this->getMessages($number,$email);
                    return response()->json($messages);
                }else{
                    $messages = $this->getMessages(null,$email);
                    return response()->json($messages);
                }

            }else{
                abort("403", "Wrong Info!");
            }

        } catch(\Illuminate\Database\QueryException $ex){
            abort("403", "Something went wrong!");
        }



    }

    public function getMessages($number=null,$email){

        $user_numbers = $this->userNumbers($email);

        if ($number == null){

            $messages = message::wherein('receiver', $user_numbers)->orderBy('id', 'desc')->simplePaginate(15);
        }else{

            if (in_array($number, $user_numbers)){

                $messages = message::where('receiver', $number)->orderBy('id', 'desc')->simplePaginate(15);
            }else{
                abort(403, 'Unauthorized action.');
            }
        }

        $ret_messages = array();
        foreach($messages as $message){
            array_push($ret_messages, array("from"=>$message['sender'],"to"=>$message['receiver'],"message"=>$message['message'],"date"=>$message['date']));

        }

        return $ret_messages;

    }
    public function userNumbers($email){
        $user_numbers = array();


        $numbers = number::all()->where('email',$email);
        foreach($numbers as $number){
            array_push($user_numbers, $number->number);
        }
        return $user_numbers;

    }
}
