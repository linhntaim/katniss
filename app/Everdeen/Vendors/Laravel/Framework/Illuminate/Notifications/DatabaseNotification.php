<?php
/**
 * Created by PhpStorm.
 * User: Antoree M
 * Date: 2018-07-05
 * Time: 14:10
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification
{
    public $incrementing = true;
}