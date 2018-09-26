<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | These values are provided by Google when signing up for reCAPTCHA.
    |
     */

    'key' => env('RECAPTCHA_KEY'),
    'secret' => env('RECAPTCHA_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Google URLs
    |--------------------------------------------------------------------------
    |
    | The URLs used by Google's reCAPTCHA service. These shouldn't need
    | to be edited, unless Google makes changes to the service.
    |
     */

    'script_url' => 'https://www.google.com/recaptcha/api.js',
    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',

    /*
    |--------------------------------------------------------------------------
    | Error Message
    |--------------------------------------------------------------------------
    |
    | The validation error message to be returned if reCAPTCHA fails.
    |
     */

    'error_message' => 'reCAPTCHA validation failed. Please try again.',

    /*
    |--------------------------------------------------------------------------
    | Log Responses
    |--------------------------------------------------------------------------
    |
    | Set `true` to write reCAPTCHA responses to Laravel log.
    |
     */

    'log_responses' => false,
];
