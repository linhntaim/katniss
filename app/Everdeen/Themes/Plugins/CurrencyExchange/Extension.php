<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\CurrencyExchange;

use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Utils\NumberFormatHelper;
use Katniss\Everdeen\Themes\Extension as BaseExtension;

class Extension extends BaseExtension
{
    const NAME = 'currency_exchange';
    const DISPLAY_NAME = 'Currency Exchange';
    const DESCRIPTION = 'Convert currency value';
    const EDITABLE = true;

    protected $mainCurrencyCode;
    protected $exchangeRates;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        $this->mainCurrencyCode = settings()->getCurrency();
        $this->exchangeRates = defPr($this->getProperty('exchange_rates'), [
            'USD' => 1,
            'VND' => 22270,
        ]);

        // check if main currency code is changed sometimes
        if (isset($this->exchangeRates[$this->mainCurrencyCode]) && $this->exchangeRates[$this->mainCurrencyCode] != 1) {
            $divideRate = $this->exchangeRates[$this->mainCurrencyCode];
            foreach ($this->exchangeRates as $currencyCode => &$rate) {
                $rate = $rate / $divideRate;
            }
            parent::save(['exchange_rates' => $this->exchangeRates]);
        }
    }

    public function register()
    {
        $exchangeRates = $this->exchangeRates;
        add_filter(NumberFormatHelper::FILTER_FORMAT_CURRENCY, new CallableObject(function ($number, $originalCurrencyCode) use ($exchangeRates) {
            return isset($exchangeRates[$originalCurrencyCode]) ? $number / $exchangeRates[$originalCurrencyCode] : $number;
        }));
        add_filter(NumberFormatHelper::FILTER_FROM_FORMAT_CURRENCY, new CallableObject(function ($number, $originalCurrencyCode) use ($exchangeRates) {
            return isset($exchangeRates[$originalCurrencyCode]) ? $number * $exchangeRates[$originalCurrencyCode] : $number;
        }));
    }

    public function viewAdminParams()
    {
        $currencies = allCurrencies();
        foreach ($currencies as $currencyCode => $currency) {
            if (!isset($this->exchangeRates[$currencyCode]) || $currencyCode == $this->mainCurrencyCode) {
                $this->exchangeRates[$currencyCode] = 1;
            }
        }
        return array_merge(parent::viewAdminParams(), [
            'exchange_rates' => $this->exchangeRates,
            'main_currency_code' => $this->mainCurrencyCode,
            'currencies' => allCurrencies(),
        ]);
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'exchange_rates',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'exchange_rates' => 'required|array',
        ]);
    }

    public function save(array $data = [], array $localizedData = [])
    {
        foreach ($data['exchange_rates'] as $currencyCode => &$rate) {
            $rate = fromFormattedNumber($rate);
        }

        return parent::save($data, $localizedData);
    }
}