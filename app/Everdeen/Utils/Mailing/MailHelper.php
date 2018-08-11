<?php

namespace Katniss\Everdeen\Utils\Mailing;

use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function queueSendTemplate($path, $params, $locale = null)
    {
        try {
            Mail::queue(new Mailable($path, $params, $locale));
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function queueSend($mailable)
    {
        try {
            Mail::queue($mailable);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function sendTemplate($path, $params, $locale = null)
    {
        try {
            Mail::send(new Mailable($path, $params, $locale));
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function send($mailable)
    {
        try {
            Mail::send($mailable);
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
