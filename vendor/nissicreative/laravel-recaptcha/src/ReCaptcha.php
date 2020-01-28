<?php

namespace Nissi\ReCaptcha;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReCaptcha
{
    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ) {
        try {
            if (app()->environment('testing')) {
                return true;
            }

            $client = new Client();

            $response = $client->post(
                config('recaptcha.verify_url'),
                [
                    'form_params' => [
                        'secret' => config('recaptcha.secret'),
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ],
                ]
            );

            $body = json_decode((string) $response->getBody());

            if (config('recaptcha.log_responses')) {
                Log::info('reCAPTCHA response: ', (array) $body);
            }

            return $body->success;
        } catch (\Exception $e) {
            if (config('recaptcha.log_responses')) {
                Log::error('reCAPTCHA exception: ', $e->getMessage());
            }

            return false;
        }
    }

}
