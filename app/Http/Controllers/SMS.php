<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SMS extends Controller
{
    public function Send($text, $to, $from=null){
        echo $text . " Sent to -> " . $to . "  ||  " ;
        return;
    }
}
