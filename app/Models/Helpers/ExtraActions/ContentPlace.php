<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 10:24
 */

namespace Katniss\Models\Helpers\ExtraActions;

class ContentPlace
{
    private static $places = [];

    private static function check($id)
    {
        if (!isset(self::$places[$id])) {
            self::$places[$id] = [];
        }
    }

    public static function add($id, CallableObject $callableObject)
    {
        if (empty($id)) return;

        self::check($id);

        self::$places[$id][] = $callableObject;
    }

    public static function flush($id, array $params = [])
    {
        $output = '';
        if (!empty(self::$places[$id])) {
            $place = self::$places[$id];
            foreach ($place as $callableObject) {
                $callableObject->unShiftParams($params);
                $output .= $callableObject->execute();
            }
        }
        return $output;
    }
}