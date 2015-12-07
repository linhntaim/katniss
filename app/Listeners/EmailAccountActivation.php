<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 08:45
 */

namespace Katniss\Listeners;

use Katniss\Models\Helpers\MailHelper;
use Katniss\Events\UserAfterRegistered;

class EmailAccountActivation
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
     * @param  UserAfterRegistered $event
     * @return void
     */
    public function handle(UserAfterRegistered $event)
    {
        MailHelper::queueSendTemplate('welcome', $event->getParamsForMailing(), $event->locale);
    }

}