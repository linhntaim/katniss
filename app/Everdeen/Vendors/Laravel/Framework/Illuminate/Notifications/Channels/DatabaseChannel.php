<?php
/**
 * Created by PhpStorm.
 * User: Antoree M
 * Date: 2018-07-05
 * Time: 15:45
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends BaseDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database')->create([
            'type' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ]);
    }
}