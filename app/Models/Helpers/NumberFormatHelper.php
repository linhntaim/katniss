<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-13
 * Time: 18:20
 */

namespace Katniss\Models\Helpers;


class NumberFormatHelper
{
    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new NumberFormatHelper();
        }
        return self::$instance;
    }

    private $type;
    private $currency;

    private function __construct()
    {
        $settings = settings();
        $this->type = $settings->getNumberFormat();
        $this->currency = $settings->getCurrency();
    }

    public function formatCurrency($number)
    {
        $number = $this->format($number);
        return $number . ' ' . $this->currency;
    }

    public function format($number)
    {
        $number = floatval($number);
        switch ($this->type) {
            case 'point_comma':
                return $this->formatPointComma($number);
            case 'point_space':
                return $this->formatPointSpace($number);
            case 'comma_point':
                return $this->formatCommaPoint($number);
            case 'comma_space':
                return $this->formatCommaSpace($number);
            default:
                return $number;
        }
    }

    public function fromFormat($formattedNumber)
    {
        $formattedNumber = str_replace(' ' . $this->currency, '', $formattedNumber);
        switch ($this->type) {
            case 'point_comma':
            case 'point_space':
                return $this->fromFormatPoint($formattedNumber);
            case 'comma_point':
            case 'comma_space':
                return $this->fromFormatComma($formattedNumber);
            default:
                return $formattedNumber;
        }
    }

    public function formatPointComma($number)
    {
        return number_format($number, 2, '.', ',');
    }

    public function formatPointSpace($number)
    {
        return number_format($number, 2, '.', ' ');
    }

    public function fromFormatPoint($formattedNumber)
    {
        return floatval(preg_replace('/[^\d\.]+/', '', $formattedNumber));
    }

    public function formatCommaPoint($number)
    {
        return number_format($number, 2, ',', '.');
    }

    public function formatCommaSpace($number)
    {
        return number_format($number, 2, ',', ' ');
    }

    public function fromFormatComma($formattedNumber)
    {
        return floatval(str_replace(',', '.', preg_replace('/[^\d\,]+/', '', $formattedNumber)));
    }

    public static function doFormat($number, $type)
    {
        switch ($type) {
            case 'point_comma':
                return self::getInstance()->formatPointComma($number);
            case 'point_space':
                return self::getInstance()->formatPointSpace($number);
            case 'comma_point':
                return self::getInstance()->formatCommaPoint($number);
            case 'comma_space':
                return self::getInstance()->formatCommaSpace($number);
            default:
                return $number;
        }
    }

    public static function doFromFormat($formattedNumber, $type)
    {
        switch ($type) {
            case 'point_comma':
            case 'point_space':
                return self::getInstance()->fromFormatPoint($formattedNumber);
            case 'comma_point':
            case 'comma_space':
                return self::getInstance()->fromFormatComma($formattedNumber);
            default:
                return $formattedNumber;
        }
    }
}