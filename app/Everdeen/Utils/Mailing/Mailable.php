<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-03-23
 * Time: 22:42
 */

namespace Katniss\Everdeen\Utils\Mailing;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Queue\SerializesModels;

class Mailable extends BaseMailable
{
    use Queueable, SerializesModels;

    const EMAIL_FROM = 'x_email_from';
    const EMAIL_FROM_NAME = 'x_email_from_name';
    const EMAIL_SUBJECT = 'x_email_subject';
    const EMAIL_TO = 'x_email_to';
    const EMAIL_TO_NAME = 'x_email_to_name';

    protected $path;

    protected $params;

    protected $useLocale;

    public function __construct($path, $params = [], $locale = null)
    {
        $this->path = $path;

        $this->useLocale = true;

        if (!isset($params[self::EMAIL_FROM])) {
            $params[self::EMAIL_FROM] = appEmail();
        }
        if (!isset($params[self::EMAIL_FROM_NAME])) {
            $params[self::EMAIL_FROM_NAME] = appName();
        }
        if (!isset($params[self::EMAIL_SUBJECT])) {
            $params[self::EMAIL_SUBJECT] = trans('label.message_from_') . appName();
        }
        if (!empty($locale)) {
            $params['locale'] = $locale;
        } else {
            $params['locale'] = currentLocaleCode();
        }

        $this->params = $params;
        $this->locale = $params['locale'];
    }

    protected function mailPath()
    {
        return 'emails.' . $this->path . ($this->useLocale ? '.' . $this->locale : '');
    }

    public function setUserLocale($value)
    {
        $this->useLocale = $value;
    }

    public function build()
    {
        $this->from($this->params[self::EMAIL_FROM], $this->params[self::EMAIL_FROM_NAME]);
        $this->subject($this->params[self::EMAIL_SUBJECT]);
        if (isset($this->params[self::EMAIL_TO_NAME])) {
            $this->to($this->params[self::EMAIL_TO]);
        } else {
            $this->to($this->params[self::EMAIL_TO], $this->params[self::EMAIL_TO_NAME]);
        }
        return $this->view($this->mailPath())
            ->with($this->params);
    }
}