<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2018-02-23
 * Time: 11:32
 */

namespace Katniss\Everdeen\Utils\Ads;

use FacebookAds\Api;
use FacebookAds\Object\Fields\AdReportRunFields;
use FacebookAds\Object\User;
use FacebookAds\Object\Ad;
use FacebookAds\Object\LeadgenForm;
use FacebookAds\Object\Lead;
use Katniss\Everdeen\Utils\FacebookHelper;

class FacebookAds
{
    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $instance = new FacebookAds();
            if ($instance->init()) {
                self::$instance = $instance;
            }
        }

        return self::$instance;
    }

    /**
     * @var FacebookHelper
     */
    private $facebookHelper;

    /**
     * @var User
     */
    private $defaultUser;

    private function __construct()
    {
        $this->facebookHelper = FacebookHelper::getInstance();
    }

    public function init()
    {
        $accessToken = $this->facebookHelper->getAccessToken();
        if ($accessToken === false) {
            return false;
        }

        $instance = Api::init(
            $this->facebookHelper->getClientId(), // App ID
            $this->facebookHelper->getClientSecret(),
            $accessToken
        );
        $instance->setDefaultGraphVersion($this->facebookHelper->getApiVersion());

        $this->defaultUser = new User($this->facebookHelper->getUserId());

        return true;
    }
}