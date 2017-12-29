<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MG_Email extends Controller
{
    var $key;

    function __construct($key = "pubkey-5be4d52a89a01e0e10dedfb1f4a064ff") {
        $this->key = $key;
    }

    function is_valid($email) {
        $response = json_decode($this->get('https://api.mailgun.net/v2/address/validate?address='.$email));
        //var_dump($response);
        return $response->is_valid ? true : false;
    }

    private function get($url) {
        $url .= '&api_key='.$this->key;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // set to 1 to verify ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status != 200) {
            // Something is wrong with the api,
            // Revert to another verification method
            // is_email (code.google.com/p/isemail/) maybe?
        }

        curl_close($ch);

        return $response;
    }
}
