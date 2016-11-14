<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-24
 * Time: 07:22
 */

namespace Katniss\Everdeen\Utils;


use Katniss\Everdeen\Models\AppOption;

class AppOptionHelper
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private static $app_options;

    public static function load()
    {
        self::$app_options = AppOption::all();
        return self::$app_options;
    }

    public static function get($key, $default = '')
    {
        if (!empty($key)) {
            $appOption = self::$app_options->where('key', $key)->first();
            if ($appOption) {
                return $appOption->value;
            }
        }

        return $default;
    }

    public static function set($key, $value, $registeredBy = null)
    {
        if (!empty($key)) {
            $appOption = self::$app_options->where('key', $key)->first();
            if (empty($appOption)) {
                $appOption = new AppOption();
                $appOption->key = $key;
            }
            $appOption->value = $value;
            $appOption->registered_by = $registeredBy;
            $appOption->save();
            return $appOption;
        }

        return false;
    }

    public static function remove($key)
    {
        if (!empty($key)) {
            return self::$app_options->where('key', $key)->delete();
        }
        return false;
    }
}