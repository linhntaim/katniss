<?php

namespace Katniss\Models\Helpers;

use Illuminate\Support\Facades\Mail;

class MailHelper
{
    const EMAIL_FROM = 'x_email_from';
    const EMAIL_FROM_NAME = 'x_email_from_name';
    const EMAIL_SUBJECT = 'x_email_subject';
    const EMAIL_TO = 'x_email_to';
    const EMAIL_TO_NAME = 'x_email_to_name';

    public static function queueSendTemplate($path, $params, $locale = null)
    {
        if (!isset($params[self::EMAIL_FROM])) {
            $params[self::EMAIL_FROM] = appEmail();
        }
        if (!isset($params[self::EMAIL_FROM_NAME])) {
            $params[self::EMAIL_FROM_NAME] = appName();
        }
        if (!isset($params[self::EMAIL_SUBJECT])) {
            $params[self::EMAIL_SUBJECT] = trans('label.message_from_') . appName();
        }
        if (!isset($params[self::EMAIL_TO])) {
            return false;
        }
        if (!empty($locale)) {
            $params['site_locale'] = $locale;
        }
        $locale = $params['site_locale'];

        try {
            Mail::queue('emails.' . $path . '.' . $locale, $params, function ($message) use ($params) {
                $message->from($params[MailHelper::EMAIL_FROM], $params[MailHelper::EMAIL_FROM_NAME]);
                $message->subject($params[MailHelper::EMAIL_SUBJECT]);
                if (isset($params[MailHelper::EMAIL_TO_NAME])) {
                    $message->to($params[MailHelper::EMAIL_TO]);
                } else {
                    $message->to($params[MailHelper::EMAIL_TO], $params[MailHelper::EMAIL_TO_NAME]);
                }
            });

            return count(Mail::failures()) > 0;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function sendTemplate($path, $params, $locale = null)
    {
        if (!isset($params[self::EMAIL_FROM])) {
            $params[self::EMAIL_FROM] = appEmail();
        }
        if (!isset($params[self::EMAIL_FROM_NAME])) {
            $params[self::EMAIL_FROM_NAME] = appName();
        }
        if (!isset($params[self::EMAIL_SUBJECT])) {
            $params[self::EMAIL_SUBJECT] = trans('label.message_from_') . appName();
        }
        if (!isset($params[self::EMAIL_TO])) {
            return false;
        }
        if (!empty($locale)) {
            $params['site_locale'] = $locale;
        }
        $locale = $params['site_locale'];

        try {
            Mail::send('emails.' . $path . '.' . $locale, $params, function ($message) use ($params) {
                $message->from($params[MailHelper::EMAIL_FROM], $params[MailHelper::EMAIL_FROM_NAME]);
                $message->subject($params[MailHelper::EMAIL_SUBJECT]);
                if (isset($params[MailHelper::EMAIL_TO_NAME])) {
                    $message->to($params[MailHelper::EMAIL_TO]);
                } else {
                    $message->to($params[MailHelper::EMAIL_TO], $params[MailHelper::EMAIL_TO_NAME]);
                }
            });

            return count(Mail::failures()) > 0;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
