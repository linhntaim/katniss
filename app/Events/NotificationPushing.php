<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-04-11
 * Time: 11:32
 */

namespace Katniss\Events;

/**
 * Class NotificationPushing
 * @package Katniss\Events
 */
class NotificationPushing extends Event
{
    /**
     * @var array
     */
    public $pushUsers;

    /**
     * @var string
     */
    public $pushUrlIndex;

    /**
     * @var array
     */
    public $pushUrlParams;

    /**
     * @var string
     */
    public $pushMessageIndex;

    /**
     * @var array
     */
    public $pushMessageParams;

    /**
     * NotificationPushing constructor.
     * @param array $users
     * @param string $urlIndex
     * @param string $messageIndex
     * @param array $urlParams
     * @param array $messageParams
     * @param array $params
     * @param string $locale
     */
    public function __construct(array $users, $urlIndex, $messageIndex, array $urlParams = [], array $messageParams = [], array $params = [], $locale)
    {
        parent::__construct($params, $locale);

        $this->pushUsers = $users;
        $this->pushUrlIndex = $urlIndex;
        $this->pushMessageIndex = $messageIndex;
        $this->pushUrlParams = $urlParams;
        $this->pushMessageParams = $messageParams;
    }
}