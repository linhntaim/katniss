<?php
/**
 * Created by PhpStorm.
 * User: Antoree M
 * Date: 2018-07-05
 * Time: 14:09
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications;


use Illuminate\Notifications\RoutesNotifications;

trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications;
}