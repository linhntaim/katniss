<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Http\Request;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Utils\Menu;
use Katniss\Everdeen\Utils\MenuItem;

class ExampleController extends ViewController
{
    public function index()
    {
        return $this->_any('home');
    }

    public function getSocialSharing()
    {
        return $this->_any('social_sharing');
    }

    public function getFacebookComments()
    {
        return $this->_any('facebook_comments');
    }

    public function getWidgets()
    {
        return $this->_any('widgets');
    }

    public function getMySettings()
    {
        $settings = settings();
        $datetimeHelper = DateTimeHelper::getInstance();
        $localeCode = $settings->getLocale();
        $locale = allLocale($localeCode);
        $countryCode = $settings->getCountry();
        $country = allCountry($countryCode);
        return $this->_any('my_settings', [
            'country' => $countryCode . ' - ' . $country['name'] . ' (+' . $country['calling_code'] . ')',
            'locale' => $localeCode . '_' . $locale['country_code'] . ' - ' . $locale['name'] . ' (' . $locale['native'] . ')',
            'timezone' => $settings->getTimezone() . ' (' . $datetimeHelper->getCurrentTimeZone() . ')',
            'price' => toFormattedNumber(22270) . ' VND = ' . toFormattedCurrency(22270, 'VND'),
            'long_datetime' => $datetimeHelper->compound(DateTimeHelper::LONG_DATE_FUNCTION, ' ', DateTimeHelper::LONG_TIME_FUNCTION),
            'short_datetime' => $datetimeHelper->compound(),
        ]);
    }
}
