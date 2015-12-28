<?php

namespace Katniss\Listeners;

use Katniss\Events\PushNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Katniss\Models\Helpers\ORTC\PushClient;
use Katniss\Models\UserNotification;

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
     * @param  PushNotification $event
     * @return void
     */
    public function handle(PushNotification $event)
    {
        $pushClient = PushClient::getInstance();

        foreach ($event->users as $user) {
            $notification = UserNotification::create([
                'user_id' => $user->id,
                'url_index' => $event->urlIndex,
                'url_params' => escapeObject($event->urlParams),
                'message_index' => $event->messageIndex,
                'message_params' => escapeObject($event->messageParams),
            ]);
            if ($notification) {
                $pushClient->queue($user->notificationChannel, $notification->toPushArray());
            }
        }

        $pushClient->send();
    }
}
