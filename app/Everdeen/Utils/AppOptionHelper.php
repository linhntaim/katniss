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

    public static function all()
    {
        return self::$app_options;
    }

    /**
     * @param int $id
     * @param bool $restrict
     * @return AppOption
     */
    public static function getById($id, $restrict = false)
    {
        $appOption = self::$app_options->where('id', $id)->first();
        if ($restrict && empty($appOption)) {
            abort(404);
        }
        return $appOption;
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
            $shouldReload = false;
            if (empty($appOption)) {
                $appOption = new AppOption();
                $appOption->key = $key;
                $shouldReload = true;
            }
            $appOption->value = $value;
            $appOption->registered_by = $registeredBy;
            $appOption->save();
            if ($shouldReload) {
                self::$app_options->push($appOption);
            }
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