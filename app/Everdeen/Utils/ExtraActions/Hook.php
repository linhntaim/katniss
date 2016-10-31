<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 10:30
 */

namespace Katniss\Everdeen\Utils\ExtraActions;

class Hook
{

    private static $actions = [];

    private static function check($id)
    {
        if (!isset(self::$actions[$id])) {
            self::$actions[$id] = [];
        }
    }

    public static function add($id, CallableObject $callableObject)
    {
        if (empty($id)) return;

        self::check($id);

        self::$actions[$id][] = $callableObject;
    }

    public static function activate($id, array $params = [])
    {
        if (!empty(self::$actions[$id])) {
            $action = self::$actions[$id];
            foreach ($action as $callableObject) {
                $callableObject->unShiftParams($params);
                $callableObject->execute();
            }
        }

        return '';
    }
}