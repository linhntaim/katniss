<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2018-02-23
 * Time: 13:54
 */

namespace Katniss\Everdeen\Utils;


use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookHelper
{
    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new FacebookHelper();
        }

        return self::$instance;
    }

    private $clientId;
    private $clientSecret;
    private $apiVersion;

    private $accessToken;
    private $userId;

    private function __construct()
    {
        $fbConfig = config('services.facebook');
        $this->clientId = $fbConfig['client_id'];
        $this->clientSecret = $fbConfig['client_secret'];
        $this->apiVersion = $fbConfig['api_version'];
        $this->accessToken = false;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = empty($accessToken) ? false : $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setAccessTokenThroughJs()
    {
        $helper = (new Facebook([
            'app_id' => $this->clientId,
            'app_secret' => $this->clientSecret,
            'default_graph_version' => $this->apiVersion,
        ]))->getJavaScriptHelper();
        try {
            $accessToken = $helper->getAccessToken();
            if (empty($accessToken)) {
                $this->accessToken = false;
            }
            $this->accessToken = $accessToken->getValue();
        } catch (FacebookSDKException $ex) {
            $this->accessToken = false;
        }
    }
}