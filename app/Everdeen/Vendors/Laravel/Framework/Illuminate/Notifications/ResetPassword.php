<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-05-17
 * Time: 08:48
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\Mailing\Mailable;

class ResetPassword extends ResetPasswordNotification
{
    protected $user;
    protected $route;

    public function __construct($token, User $user)
    {
        parent::__construct($token);

        $this->user = $user;
        $this->route = 'password/reset/{token}';
    }

    public function toMail($notifiable)
    {
        $locale = $this->user->settings->locale;
        return new Mailable('forgot_password', [
            Mailable::EMAIL_FROM => appEmail(),
            Mailable::EMAIL_FROM_NAME => appName(),
            Mailable::EMAIL_TO => $this->user->email,
            Mailable::EMAIL_TO_NAME => $this->user->shown_name,
            Mailable::EMAIL_SUBJECT => '[' . appName() . '] ' . trans('label.reset_password'),
            'reset_url' => homeUrl($this->route, ['token' => $this->token], $locale),
            'site_url' => homeUrl(null, [], $locale),
            'site_name' => appName(),
            'site_locale' => $locale,
        ], $locale);
    }
}