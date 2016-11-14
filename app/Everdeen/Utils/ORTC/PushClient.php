<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-24
 * Time: 01:03
 */

namespace Katniss\Everdeen\Utils\ORTC;


class PushClient
{
    /**
     * @var PushClient
     */
    private static $instance;

    /**
     * @return PushClient
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new PushClient();
        }

        return self::$instance;
    }

    const PREFIX_CHANNEL_NORMAL = 'nml_';
    const PREFIX_CHANNEL_NOTIFICATION = 'ntf_';
    const PREFIX_CHANNEL_MESSAGE = 'msg_';

    public static function generateChannelKey($prefix = self::PREFIX_CHANNEL_NORMAL)
    {
        return uniqid($prefix, true);
    }

    /**
     * @var Realtime
     */
    private $connector;

    /**
     * @var array
     */
    private $messages;

    private function __construct()
    {
        $this->messages = [];
        $this->connector = new Realtime(appOrtcServer(), appOrtcClientKey(), appOrtcClientSecret(), appOrtcClientToken());
    }

    /**
     * @param string $channel
     * @param mixed $data
     * @return bool
     */
    public function sendImmediately($channel, $data)
    {
        if ($this->connector->auth([$channel => 'w'])) {
            if (is_array($data) || is_object($data)) {
                $data = json_encode($data);
            }
            if ($this->connector->send($channel, $data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $channel
     * @param mixed $data
     * @return bool
     */
    public function sendMultichannelImmediately(array $channels, $data)
    {
        $writeChannels = [];
        foreach ($channels as $channel) {
            $writeChannels[$channel] = 'w';
        }

        if ($this->connector->auth($writeChannels)) {
            if (is_array($data) || is_object($data)) {
                $data = json_encode($data);
            }
            foreach ($channels as $channel) {
                if (!$this->connector->send($channel, $data)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string $channel
     * @param mixed $data
     * @return PushClient
     */
    public function queue($channel, $data)
    {
        if (!isset($this->messages[$channel])) {
            $this->messages[$channel] = [];
        }

        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        $this->messages[$channel][] = $data;

        return $this;
    }

    /**
     * @param bool $deleteAfter
     * @return bool
     */
    public function send()
    {
        $queuedMessages = $this->messages;
        $this->messages = [];
        foreach ($queuedMessages as $channel => $messages) {
            if ($this->connector->auth([$channel => 'w'])) {
                foreach ($messages as $message) {
                    if (!$this->connector->send($channel, $message)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}