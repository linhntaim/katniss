<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-04-30
 * Time: 13:00
 */

namespace Katniss\Everdeen\Utils;


use GuzzleHttp\Client;

class PassportClient
{
    protected $passportUrl;
    protected $tokenType;
    protected $accessToken;
    protected $assoc;

    public function __construct()
    {
        $token = request()->session()->get(AppConfig::KEY_PASSPORT_TOKEN, null);
        if (empty($token)) {
            abort(500);
        }

        $this->tokenType = $token['token_type'];
        $this->accessToken = $token['access_token'];
        $this->passportUrl = config('services.account_passport.url');
        $this->assocMode(false);
    }

    protected function assocMode($assoc = true)
    {
        $this->assoc = $assoc;
    }

    protected function url($relativeUrl, $api = true)
    {
        return $api ? $this->passportUrl . '/api/' . $relativeUrl : $this->passportUrl . '/' . $relativeUrl;
    }

    public function get($url, $params = [], $api = true)
    {
        $http = new Client([
            'verify' => false
        ]);
        $response = $http->get($this->url($url, $api) . '?' . http_build_query($params), [
            'headers' => [
                'Authorization' => $this->tokenType . ' ' . $this->accessToken,
            ],
        ]);
        return json_decode((string)$response->getBody(), $this->assoc);
    }

    public function delete($url, $params = [], $api = true)
    {
        $http = new Client([
            'verify' => false
        ]);
        $response = $http->delete($this->url($url, $api) . '?' . http_build_query($params), [
            'headers' => [
                'Authorization' => $this->tokenType . ' ' . $this->accessToken,
            ],
        ]);
        return json_decode((string)$response->getBody(), $this->assoc);
    }
}