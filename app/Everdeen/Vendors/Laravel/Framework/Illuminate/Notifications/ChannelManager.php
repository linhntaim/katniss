<?php
/**
 * Created by PhpStorm.
 * User: Antoree M
 * Date: 2018-07-05
 * Time: 15:44
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications;

use Illuminate\Notifications\ChannelManager as BaseChannelManager;

class ChannelManager extends BaseChannelManager
{
    protected function createDatabaseDriver()
    {
        return $this->app->make(Channels\DatabaseChannel::class);
    }
}