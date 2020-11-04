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

    public function SmsForTest(){

       return array(
            "Do you know why my world is so perfect? Because you are my world!",
            "I asked God to send me the best girlfriend in the world, and He sent me a wonderful woman who has become my true friend, a passionate lover, a caring partner and the only one for me!",
            "Thank you for being in my life.", 
            "My darling, what can be better than looking into your eyes and seeing there the reflection of your soul? You made me the happiest human on Earth.", 
            "Hello Sweetheart!",
            "I hope that this cute message will make you smile: I want you to know that while the world keeps spinning and the times are changing, my love for you will last forever.", 
            "Do you know when I realized that I love you? When I saw you as the woman with whom I will spend forever and eternity.", 
            "Allow me to be romantic and tell you that a starry sky is empty without you, the sun does not warm, and nothing pleases me, only you fill my life with sense.", 
            "Beloved, my love for you is unconditional, it grows from the depths of my heart, and without your affection and warmth my heart stops beating, all I ask for is you by my side forever.", 
            "Do you know what the best girlfriend in the world deserves? She needs a caring, intelligent and affectionate man.", 
            "My congratulations, you got me!",
            "Your cute smile melted my icy heart.", 
            "Thanks for all the happiness that you gave me, I love you.", 
            "Dear World:  Thank you so much for the amazing partner.", 
            "I couldnt dream of a more romantic, sensual, caring and sweet second half.", 
            "You are the queen of my heart and the mistress of my fate.", 
            "The best moment of my life was when I first laid eyes on you, my sweet girl!",
            "I would have given you the most beautiful flowers as a sign of my deep love for you, but they pale in comparison to your beauty.", 
            "I would compare you to a star, but the most brilliant of stars is dim compared to your dazzling eyes.", 
            "So Ill just tell you, in my plain humble way, that your love is the most precious thing in my life.", 
            "I have always thought that a person can only experience happiness once in a lifetime, but with you I realized that happiness for me is every minute, every second, every romantic day that I spend with you.", 
            "You know, the world can be saved only by love, but my love for you is so strong that it can heal millions of broken hearts.", 
            "You are my everything, I love you.", 
            "When you meet the woman of your dreams, you feel like theres no way your life could ever get better.", 
            "But then comes the moment when that amazing woman becomes a beloved wife.", 
            "I love you, darling.", 
            "You are my dream come true.", 
            "I havent met anyone cuter, more intelligent, romantic, sweet, or understanding  than you.", 
            "I still cannot understand why I deserve such happiness, but I am so thankful that our lives collided.", 
            "I hope the bliss we feel today will never end.", 
            "Come what may, know that I love you madly!",
            "I live for you!",
            "Your image is imprinted in my mind.", 
            "My heart whispers your name, and when I close my eyes, I see your face.", 
            "You are the best part of me, and I love you more than I ever thought possible.", 
            "You know, with you, I realized what it means to live life to the fullest and to enjoy every breath.", 
            "You helped me to see the world with happy eyes, my love, my sweet girl.", 
            "I am crazy about you.", 
            "There is nothing more cute and romantic in this world than to see your sleepy eyes every morning and to hug you.", 
            "If you promise me another 60 years, then I need nothing more in this world.", 
            "Only fools believe that love makes a person vulnerable; love makes a person strong and courageous.", 
            "Your love made me a better person.", 
            "Because of you, I have grown into the person whom I always wanted to be.", 
            "Stars, seas, oceans, all the wonders of the world, Ill throw at your feet for the sake of your beautiful smile.", 
            "When I met you for the first time, I craved your attention, but I could not even imagine that you would change my life so radically.", 
            "You have become the most important person in my life, and I cannot live without you.", 
            "If I was rich, I would give everything away for the sake of your sweet affection.", 
            "I would throw away all gold in the world for your smile.", 
            "Alas, I cannot, so I give you the most valuable thing in my life, my heart.", 
            "You are my first, last and only love.", 
            "My only dream is to share with you all of the sunrises and sunsets for the rest of my life.", 
            "You lift my spirits when I feel down.", 
            "You share joy with me when Im over the moon.", 
            "You are with me in good and bad times, and you are a reminder of how wonderful life can be.", 
            "Only one single phrase makes my heart beat faster, it is your name and the word “forever,” joined together.", 
            "In the next life, I will face the storms and arrows of life to find you faster, so I can spend every second of my life with you, my beloved.", 
            "You cutie pie!",
            "I swear, moments with you are pure magic.", 
            "I soar to new heights of of happiness, love and passion when you are with me.", 
            "For me, it is better not to live at all than to live without you.", 
            "My sweet: you are a rare combination of a sharp mind, a kind heart, and a sexy body.", 
            "You are the best girlfriend!",
            "Only with you, I learned to breathe!",
            "Im smitten with you!",
            "Youre my number one!",
            "Your touches warm me better than the sun!",
            "Im crazy in love with you!",
            "Youre the queen of my heart.", 
            "Forever isnt long enough with you.", 
            "I cant bear to be apart from you.", 
            "You are my heart, my lungs, my everything.", 
            "Each time we part, my heart calls out for you!",
            "Im totally into you, sweetheart!",
            "Only one thing in the world can make me happy, it is to see your eyes every morning for the next 50 years!",
            "You are my life, my love.", 
            "Wherever I am and whatever I do, I always yearn for you.", 
            "My life is empty without you.", 
            "When I look at you, I realize that beauty truly will save the world!",
            "At least, your outer and inner beauty saved my heart!",
            "I am so happy to have you in my life!",
            "I am ready to spend each breath telling you how much I love you.", 
            "Honestly, sometimes you just cant get too mushy.", 
            "Whether youre currently miles apart, fresh off of a… shall we say “collaborative disagreement,” or just havent done anything particularly romantic lately, theres no time like the present to send a short line of love to her text inbox to remind her of the passion you felt when your love was brand new.", 
            "The best feeling on Earth is to hold you in my arms and bloom, nourished by your love.", 
            "You are the love of my life.", 
            "When were together and you hold my hand, Im so happy that I cannot tell where I end and you begin.", 
            "If I was asked where I want to spend eternity, the answer would be simple, in your arms.", 
            "You are the answer to my prayers, the most beautiful gift!",
            "I love you from the bottom of my heart!",
            "Only with you can I be everything I want to be and need to be.", 
            "I think youre the one.", 
            "Do you know why the moon doesnt shine during the day? Because the brilliance of your beautiful eyes illuminates all around!",
            "You are incredible!",
            "When Im with you, the only thing I want to do is to hold you tight, keep you warm and never let you go!",
            "Give your heart to me and Ill give you all the joys of the world!",
            "There is no other for me.", 
            "I know that our love is strong enough to last forever!",
            "I love you more than life.", 
            "Darling, without you everything is meaningless, only you stir my soul.", 
            "If you were a flower, I would never let you wither, because I could never lose you!",
            "You take my breath away!",
            "I used to think that the Northern Lights are the most beautiful thing in the world, but your smile dazzles me more than all the lights of heaven and Earth.", 
            "I am under your spell.", 
            "I would rather choose a moment on Earth with you than an eternity in paradise without you.", 
            "I think of you eight days a week, 25 hours a day!",
            "I am all about you!",
            "I could spend forever domesticated in the captivity of your eyes and lips.", 
            "You are awesome!",
            "I am proud to call you mine.", 
            "There is no more beautiful, understanding and astonishing girl in the world!",
            "You are my everything.", 
            "I cant even conceive of life without you.", 
            "You are my heart, my moon, my sun, my stars, and I am drunk with love for you, my beloved!",
            "If I were the worlds greatest artist, I could not find a way to portray your beautiful features.", 
            "If I were a writer, I could not find the words to describe my love for you.", 
            "You are my perfect match!",
            "When I met you, I lost my peace, sleep, and my heart.", 
            "Meeting with you is the most beautiful thing that happened to me in life.", 
            "Do you know what can make me the happiest person in the world? Knowing you are always by my side.", 
            "My life is empty without you.", 
            "You keep me sane and calm my soul.", 
            "When I met you my world finally clicked into place and I am thankful every day for it.");
            
            

    }

       
    
    public function test(){
        if ($this->strpos_arr("bbbb", $this->SmsForTest()) === false){
            return "makaynach";
        }else{
            return "kayna";
        }
		
    }


    private function strpos_arr($haystack, $needle) {

        foreach($needle as $what) {
            if(($pos = strpos($haystack, $what))!==false){
                return true;
            }
        }
        return false;
    }

    public function logMessage($from, $to, $text){

        $time = Carbon::now();
        number::where('number', '=', $to)->update(['last_checked' => $time]);

        if (number::where('number','=',$to)->where('is_private','=',true)->count() > 0){
            $is_private = true;
        }else{
            $is_private = false;
        }

   

            // check if number verification


            if ($this->strpos_arr($text, $this->SmsForTest()) === false){
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

    public function testSendSMS(){
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.nexmo.com/verify/json?api_key=0b8c3e63&api_secret=BDYrXLxn3SH4BtA1&number=16137779527&brand=codecamp&code_length=4",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
   
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
    public function textnowPostal(){
        //Log::info($_REQUEST);
        
        if (Input::has('plain_body') and Input::has('to') and Input::has('subject')){

            if (strpos(Input::get('subject'), "Message from") !== false){
            $text = Input::get('plain_body');
            $toemail = Input::get('to');
            $subject = Input::get('subject');

            $subject = str_replace("Message from ","",$subject);
            $subject = str_replace("textnow","",$subject);
            
            $subject = str_replace("+","",$subject);
            $from = $subject;


            $number = number::where('network_login','=',$toemail)->first();
            $to = $number["number"];




        if ($number["email"] == "SMS-Verification"){
            $url = "https://sms-verification.net/log/$from/$to/$text";


        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec ($ch);
            $err = curl_error($ch);  //if you need
            curl_close ($ch);
            return $response;

                
        }else{

            $this->logMessage($from, $to, $text);

            //$this->sendCallback($from,$to,$text);
        }
    }

    if (strpos(Input::get('subject'), "Welcome to TextNow") !== false){
  


        preg_match('#\(https(.*?)\)#', Input::get('plain_body'), $matches);
        $url = trim($matches[0], '()');


 
        Log::info($url);
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

    }
            
 

    }

    public function textnow(){
        //Log::info($_REQUEST);
        
        if (Input::has('body-plain') and Input::has('To') and Input::has('Subject')){

            if (strpos(Input::get('Subject'), "Message from") !== false){
            $text = Input::get('body-plain');
            $toemail = Input::get('To');
            $subject = Input::get('Subject');

            $subject = str_replace("Message from ","",$subject);
            $subject = str_replace("textnow","",$subject);
            
            $subject = str_replace("+","",$subject);
            $from = $subject;


            $number = number::where('network_login','=',$toemail)->first();
            $to = $number["number"];




        if ($number["email"] == "SMS-Verification"){
            $url = "https://sms-verification.net/log/$from/$to/$text";


        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec ($ch);
            $err = curl_error($ch);  //if you need
            curl_close ($ch);
            return $response;

                
        }else{

            $this->logMessage($from, $to, $text);

            //$this->sendCallback($from,$to,$text);
        }
    }

    if (strpos(Input::get('Subject'), "Welcome to TextNow") !== false){
  


        preg_match('#\(https(.*?)\)#', Input::get('body-plain'), $matches);
        $url = trim($matches[0], '()');


 
        Log::info($url);
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

    }
            
 

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
            $url = $user['callback_url'] . "?sender=" . urldecode($from) . "&receiver=" . urldecode($to) . "&message=" . urldecode($message);
         
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
        //if (strlen($str) > 6 && is_numeric($str)){
        //    $len=strlen($str);
        //    $val=substr($str,0,($len-4));
        //    $val=$val . "XXXX";
        //    return $val;
        //}
        return $str;
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
