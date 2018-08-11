<?php

namespace Katniss\Everdeen\Validators;

use GuzzleHttp\Client;

class GoogleReCaptcha
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $response = (new Client())->post(config('services.google_re_captcha.url'), [
            'form_params' => [
                'secret' => config('services.google_re_captcha.secret'),
                'response' => $value,
            ],
        ]);

        $body = json_decode((string)$response->getBody());
        return $body !== false ? $body->success : false;
    }
}