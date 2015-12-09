<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 17:13
 */

namespace Katniss\Listeners;

use Katniss\Models\Helpers\MailHelper;
use Katniss\Events\UserPasswordChanged;

class EmailAccountPassword
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
     * @param  UserPasswordChanged $event
     * @return void
     */
    public function handle(UserPasswordChanged $event)
    {
        MailHelper::queueSendTemplate('user_password_changed', $event->getParamsForMailing(), $event->locale);
    }
}