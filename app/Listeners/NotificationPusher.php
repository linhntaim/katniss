<?php

namespace Katniss\Listeners;

use Katniss\Events\NotificationPushing;
use Katniss\Everdeen\Utils\ORTC\PushClient;
use Katniss\Everdeen\Models\UserNotification;

class NotificationPusher
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationPushing $event
     * @return void
     */
    public function handle(NotificationPushing $event)
    {
        $pushClient = PushClient::getInstance();

        foreach ($event->pushUsers as $user) {
            $notification = UserNotification::create([
                'user_id' => $user->id,
                'url_index' => $event->pushUrlIndex,
                'url_params' => escapeObject($event->pushUrlParams),
                'message_index' => $event->pushMessageIndex,
                'message_params' => escapeObject($event->pushMessageParams),
            ]);
            if ($notification) {
                $pushClient->queue($user->notificationChannel, $notification->toPushArray());
            }
        }

        $pushClient->send();
    }
}
