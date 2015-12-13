<?php

namespace Katniss\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Katniss\Http\Requests;
use Katniss\Http\Controllers\ViewController;

class SettingsController extends ViewController
{
    public function index(Request $request)
    {
        $this->theme->title(trans('pages.my_settings_title'));
        $this->theme->description(trans('pages.my_settings_desc'));

        return view($this->themePage('my_settings'), [
            'settings' => app('settings'),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'locale' => 'required|in:' . implode(',', allSupportedLocaleCodes()),
            'timezone' => 'required',
            'currency' => 'required|in:' . implode(',', allCurrencyCodes()),
            'number_format' => 'required|in:' . implode(',', allNumberFormats()),
            'first_day_of_week' => 'required|integer|min:0|max:6',
            'long_date_format' => 'required|integer|min:0|max:3',
            'short_date_format' => 'required|integer|min:0|max:3',
            'long_time_format' => 'required|integer|min:0|max:4',
            'short_time_format' => 'required|integer|min:0|max:4',
        ]);

        if ($validator->fails()) {
            return redirect(homeUrl('custom-settings'))
                ->withErrors($validator);
        }

        $settings = settings();
        $settings->setLocale($request->input('locale'));
        $settings->setCountry($request->input('country'));
        $settings->setTimezone($request->input('timezone'));
        $settings->setCurrency($request->input('currency'));
        $settings->setNumberFormat($request->input('number_format'));
        $settings->setFirstDayOfWeek($request->input('first_day_of_week'));
        $settings->setLongDateFormat($request->input('long_date_format'));
        $settings->setShortDateFormat($request->input('short_date_format'));
        $settings->setLongTimeFormat($request->input('long_time_format'));
        $settings->setShortTimeFormat($request->input('short_time_format'));

        $settings->storeUser();
        $settings->storeSession();
        return $settings->storeCookie(redirect(homeUrl('my-settings', [], $settings->getLocale())));
    }
}
