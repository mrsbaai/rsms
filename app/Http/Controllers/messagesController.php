<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\message;
use App\number;
use App\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\SendersBlacklist;
use App\StringsBlacklist;
use Illuminate\Support\Facades\Log;

use App\Libraries\Session;



class messagesController extends Controller
{
    //


    public function logMessage($from, $to, $text){

            $time = Carbon::now();
            number::where('number', '=', $to)->update(['last_checked' => $time]);

            if (number::where('number','=',$to)->where('is_private','=',true)->count() > 0){
                $is_private = true;
            }else{
                $is_private = false;
            }

            // check if number verification
            if (strpos($text,"RSMSCODE-") === false){
                // check if not spam
                if ($this->isSpam($from,$to,$text) == false){
                    $message = new message();
                    $message->message = $text;
                    $message->sender = $from;
                    $message->receiver = $to;
                    $message->date = $time;
                    $message->is_private = $is_private;
                    $message->save();

                    $this->sendCallback($from,$to,$text);
                }

            }

            return "";


    }

    public function textnow(){
    
if (Input::has('body-plain') and Input::has('body-plain') and Input::has('body-plain')){
    $text = Input::get('body-plain');
    $toemail = Input::get('To');
    $subject = Input::get('Subject');
    $split = explode("Message from ", $subject, 2);
    $from = $split[1];

    $number = number::where('network_login','=',$toemail)->first();
    $to = $number["number"];



}


    Log::info("From: $from | To: $to | Message: $text");

    }

    public function genericLogSMS($from = null,$to = null,$text = null){


        if ($from <> null and $to <> null and $text <> null){
            $text = urldecode($text);
            $this->logMessage($from, $to, $text);
        }else{
            return "Nice Try ;)";
        }

        return "success!";
    }

    public function tropo(){



        $from = null;
        $to = null;
        $text = null;

        $session = new Session();

        $text = $session->getInitialText();
        $from = $session->getFrom();
        $to = $session->getTo();


        Log::info($text);

        if ($from <> null and $to <> null and $text <> null){
            $this->logMessage($from, $to, $text);
        }else{
            return "";
        }

        return "success!";
    }

    public function bandwidth(){

        Log::info($_REQUEST);

        $from = null;
        $to = null;
        $text = null;

        if(Input::has('message')){$text = Input::get('message');}
        if(Input::has('receiver')){$to = Input::get('receiver');}
        if(Input::has('sender')){$from = Input::get('sender');}

        if ($from <> null and $to <> null and $text <> null){
            $this->logMessage($from, $to, $text);
        }else{
            return "";
        }

        return "success!";
    }
    private function sendCallback($from,$to,$message){
        $number = number::where('number','=',$to)->first();
        $user = user::where('email','=', $number['email'])->first();

        if ($user['callback_url'] <> null and $user['callback_url'] <> ""){
            $url = $user['callback_url'] . "?sender=" . $from . "&receiver=" . $to . "&message=" . $message;

            $curlSession = curl_init();


            curl_setopt($curlSession, CURLOPT_URL, $url);

            curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
            curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curlSession, CURLOPT_MAXREDIRS,5); // return into a variable

            curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

            $ret = curl_exec($curlSession);
            curl_close($curlSession);

        }

        return "";
    }

    private function isSpam($from,$to,$new_message){

        if (is_numeric($to) == false){return true;}

        $badSenders = SendersBlacklist::all();
        $badStrings = StringsBlacklist::all();



        foreach ($badSenders as $badSender){
            if ( $badSender['number'] == $from){return true;}

        }
        foreach ($badStrings as $badString){
            if (strpos(strtoupper($new_message),strtoupper($badString['string']))){return true;}
        }

        $messages = message::all()->sortByDesc('id')->take(30);
        $count = 0;
        foreach($messages as $message){
            if  (strtoupper($message['message']) == strtoupper($new_message)){$count = $count + 1;}
            if ($count >= 3){return true;}
        }

        return false;
    }



    public function getPublicMessages($number=null,$tag=null){

        if ($number <> null){
            $messages = message::where('is_private',false)->where('receiver', "=", $number)->orderBy('date', 'desc')->simplePaginate(15);
        }else{
            if ($tag <> null){
                $messages = message::where('is_private',false)->where('message', 'LIKE', "%$tag%")->orderBy('date', 'desc')->simplePaginate(15);
            }else{
                $messages = message::where('is_private',false)->orderBy('date', 'desc')->simplePaginate(15);
            }

        }


        foreach($messages as $message){
            //$message['date'] = $this->nicetime($message['date']);
            $message['sender'] = $this->hideLast($message['sender']);
        }
        return $messages;

    }






    public function getUserMessages($number){
        $userController = new userController();
        $user_numbers = $userController->userNumbers();

        if ($number == null){

            $messages = message::wherein('receiver', $user_numbers)->orderBy('date', 'desc')->simplePaginate(15);
        }else{

            if (in_array($number, $user_numbers)){

                $messages = message::where('receiver', $number)->orderBy('date', 'desc')->simplePaginate(15);
            }else{
                abort(403, 'Unauthorized action.');
            }
        }



        //foreach($messages as $message){
            //$message['date'] = $this->nicetime($message['date']);
        //}

        return $messages;

    }


    public function lastMessage(){

        $message = message::where('is_private',false)->orderBy('date', 'desc')->first();

        return response()->json($message);
    }

    public function newMessages($id,$num){
        $message = message::where('id',$id)->first();
        $lastDate = $message['date'];

        if ($lastDate == null){
            return;
        }


        if (Auth::check()){

            $userController = new userController();
            $user_numbers = $userController->userNumbers();



            if (is_numeric($num) == true){
                $messages = message::where('is_private',true)->where('receiver',$num)->whereIn('receiver', $user_numbers)->where('date' , '>', $lastDate)->get()->sortByDesc('date');
            }else{
                $messages = message::where('is_private',true)->whereIn('receiver', $user_numbers)->where('date' , '>', $lastDate)->get()->sortByDesc('date');
            }

        }else{
            if (is_numeric($num) == true){
                $messages = message::where('is_private',false)->where('receiver',$num)->where('date' , '>', $lastDate)->get()->sortByDesc('date');
            }else{
                $messages = message::where('is_private',false)->where('date' , '>', $lastDate)->get()->sortByDesc('date');
            }

            foreach($messages as $message){
                $message['sender'] = $this->hideLast($message['sender']);
            }

        }

        return response()->json($messages);


    }

    private function hideLast($str){
        if (strlen($str) > 6 && is_numeric($str)){
            $len=strlen($str);
            $val=substr($str,0,($len-4));
            $val=$val . "XXXX";
            return $val;
        }
    }

    private function nicetime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");
        date_default_timezone_set("UTC");
        $now             = time();
        $unix_date         = strtotime($date);

        if(empty($unix_date)) {
            return "Bad date";
        }
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }


}
