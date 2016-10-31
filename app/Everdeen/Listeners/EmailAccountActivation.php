<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 08:45
 */

namespace Katniss\Everdeen\Listeners;

use Katniss\Everdeen\Utils\MailHelper;
use Katniss\Everdeen\Events\UserAfterRegistered;

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
        $viewPath = $event->fromSocial ? 'welcome_social' : 'welcome';
        MailHelper::queueSendTemplate($viewPath, $event->getParamsForMailing(), $event->locale);
    }

}