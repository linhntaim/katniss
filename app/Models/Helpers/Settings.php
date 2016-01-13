<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-11
 * Time: 05:39
 */

namespace Katniss\Models\Helpers;


use Illuminate\Http\Request;
use Illuminate\Session\Store;

class Settings
{
    public $locale;
    public $country;
    public $timezone;
    public $currency;
    public $number_format;
    public $first_day_of_week;
    public $long_date_format;
    public $short_date_format;
    public $long_time_format;
    public $short_time_format;

    protected $changingDataStore;

    public function __construct()
    {
        $this->locale = config('app.locale');
        $this->country = config('katniss.settings.country');
        $this->timezone = config('app.timezone');
        $this->currency = config('katniss.settings.currency');
        $this->number_format = config('katniss.settings.number_format');
        $this->first_day_of_week = config('katniss.settings.first_day_of_week');
        $this->long_date_format = config('katniss.settings.long_date_format');
        $this->short_date_format = config('katniss.settings.short_date_format');
        $this->long_time_format = config('katniss.settings.long_time_format');
        $this->short_time_format = config('katniss.settings.short_time_format');

        $this->changingDataStore = [];
    }

    public function setLocale($locale)
    {
        $old = $this->locale;
        if (!empty($locale)) {
            $this->locale = $locale;
        }
        if ($old != $locale) {
            $this->changingDataStore['locale'] = $this->locale;
        }
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setCountry($country)
    {
        $old = $this->country;
        if (!empty($country)) {
            $this->country = $country;
        }
        if ($old != $country) {
            $this->changingDataStore['country'] = $this->country;
        }
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setTimezone($timezone)
    {
        $old = $this->timezone;
        if (!empty($timezone)) {
            $this->timezone = $timezone;
        }
        if ($old != $timezone) {
            $this->changingDataStore['timezone'] = $this->timezone;
        }
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setCurrency($currency)
    {
        $old = $this->currency;
        if (!empty($currency)) {
            $this->currency = $currency;
        }
        if ($old != $currency) {
            $this->changingDataStore['currency'] = $this->currency;
        }
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setNumberFormat($number_format)
    {
        $old = $this->number_format;
        if (!empty($number_format)) {
            $this->number_format = $number_format;
        }
        if ($old != $number_format) {
            $this->changingDataStore['number_format'] = $this->number_format;
        }
    }

    public function getNumberFormat()
    {
        return $this->number_format;
    }

    public function setFirstDayOfWeek($first_day_of_week)
    {
        $old = $this->first_day_of_week;
        if (!empty($first_day_of_week) || $first_day_of_week == 0) {
            $this->first_day_of_week = $first_day_of_week;
        }
        if ($old != $first_day_of_week) {
            $this->changingDataStore['first_day_of_week'] = $this->first_day_of_week;
        }
    }

    public function getFirstDayOfWeek()
    {
        return $this->first_day_of_week;
    }

    public function setLongDateFormat($long_date_format)
    {
        $old = $this->long_date_format;
        if (!empty($long_date_format) || $long_date_format === 0) {
            $this->long_date_format = $long_date_format;
        }
        if ($old != $long_date_format) {
            $this->changingDataStore['long_date_format'] = $this->long_date_format;
        }
    }

    public function getLongDateFormat()
    {
        return $this->long_date_format;
    }

    public function setShortDateFormat($short_date_format)
    {
        $old = $this->short_date_format;
        if (!empty($short_date_format) || $short_date_format === 0) {
            $this->short_date_format = $short_date_format;
        }
        if ($old != $short_date_format) {
            $this->changingDataStore['short_date_format'] = $this->short_date_format;
        }
    }

    public function getShortDateFormat()
    {
        return $this->short_date_format;
    }

    public function setLongTimeFormat($long_time_format)
    {
        $old = $this->long_time_format;
        if (!empty($long_time_format) || $long_time_format === 0) {
            $this->long_time_format = $long_time_format;
        }
        if ($old != $long_time_format) {
            $this->changingDataStore['long_time_format'] = $this->long_time_format;
        }
    }

    public function getLongTimeFormat()
    {
        return $this->long_time_format;
    }

    public function setShortTimeFormat($short_time_format)
    {
        $old = $this->short_time_format;
        if (!empty($short_time_format) || $short_time_format === 0) {
            $this->short_time_format = $short_time_format;
        }
        if ($old != $short_time_format) {
            $this->changingDataStore['short_time_format'] = $this->short_time_format;
        }
    }

    public function getShortTimeFormat()
    {
        return $this->short_time_format;
    }

    public function getChangingDataStore()
    {
        return $this->changingDataStore;
    }

    public function fromUser()
    {
        if (isAuth()) {
            $userSettings = authUser()->settings;
            $this->setLocale($userSettings->locale);
            $this->setCountry($userSettings->country);
            $this->setTimezone($userSettings->timezone);
            $this->setCurrency($userSettings->currency);
            $this->setNumberFormat($userSettings->number_format);
            $this->setFirstDayOfWeek($userSettings->first_day_of_week);
            $this->setLongDateFormat($userSettings->long_date_format);
            $this->setShortDateFormat($userSettings->short_date_format);
            $this->setLongTimeFormat($userSettings->long_time_format);
            $this->setShortTimeFormat($userSettings->short_time_format);

            return true;
        }

        return false;
    }

    public function fromSession(Store $session)
    {
        if ($session->has('settings')) {
            $this->setLocale($session->get('settings.locale'));
            $this->setCountry($session->get('settings.country'));
            $this->setTimezone($session->get('settings.timezone'));
            $this->setCurrency($session->get('settings.currency'));
            $this->setNumberFormat($session->get('settings.number_format'));
            $this->setFirstDayOfWeek($session->get('settings.first_day_of_week'));
            $this->setLongDateFormat($session->get('settings.long_date_format'));
            $this->setShortDateFormat($session->get('settings.short_date_format'));
            $this->setLongTimeFormat($session->get('settings.long_time_format'));
            $this->setShortTimeFormat($session->get('settings.short_time_format'));
            return true;
        }
        return false;
    }

    public function fromCookie(Request $request)
    {
        $this->setLocale($request->cookie('settings_locale'));
        $this->setCountry($request->cookie('settings_country'));
        $this->setTimezone($request->cookie('settings_timezone'));
        $this->setCurrency($request->cookie('settings_currency'));
        $this->setNumberFormat($request->cookie('settings_number_format'));
        $this->setFirstDayOfWeek($request->cookie('settings_first_day_of_week'));
        $this->setLongDateFormat($request->cookie('settings_long_date_format'));
        $this->setShortDateFormat($request->cookie('settings_short_date_format'));
        $this->setLongTimeFormat($request->cookie('settings_long_time_format'));
        $this->setShortTimeFormat($request->cookie('settings_short_time_format'));

        return $request->hasCookie('settings_locale');
    }

    public function storeUser()
    {
        if (isAuth()) {
            if (count($this->changingDataStore) > 0) {
                $userSettings = authUser()->settings;
                foreach ($this->changingDataStore as $key => $value) {
                    $userSettings->{$key} = $value;
                }
                $userSettings->save();
            }
        }
    }

    public function storeSession()
    {
        if (count($this->changingDataStore) > 0) {
            $sessionDataStore = [];
            foreach ($this->changingDataStore as $key => $value) {
                $sessionDataStore['settings.' . $key] = $value;
            }
            session($sessionDataStore);
        }
    }

    /**
     * @param \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse $response
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function storeCookie($response)
    {
        if (count($this->changingDataStore) > 0) {
            foreach ($this->changingDataStore as $key => $value) {
                $response->withCookie(cookie()->forever('settings_' . $key, $value));
            }
        }
        return $response;
    }

    public function clearChanges()
    {
        $this->changingDataStore = [];
    }

    public function makeAllChanges()
    {
        $this->changingDataStore = [
            'locale' => $this->locale,
            'country' => $this->country,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
            'number_format' => $this->number_format,
            'first_day_of_week' => $this->first_day_of_week,
            'long_date_format' => $this->long_date_format,
            'short_date_format' => $this->short_date_format,
            'long_time_format' => $this->long_time_format,
            'short_time_format' => $this->short_time_format,
        ];
    }

    public function makeOnlyChanges(array $properties)
    {
        $this->changingDataStore = [];
        foreach ($properties as $property) {
            $this->changingDataStore[$property] = $this->{$property};
        }
    }

    public function compare(Settings $settings)
    {
        $properties = [
            'locale',
            'country',
            'timezone',
            'currency',
            'number_format',
            'first_day_of_week',
            'long_date_format',
            'short_date_format',
            'long_time_format',
            'short_time_format',
        ];
        $diff = [];
        foreach ($properties as $property) {
            if ($this->{$property} != $settings->{$property}) {
                $diff[] = $property;
            }
        }
        return $diff;
    }
}