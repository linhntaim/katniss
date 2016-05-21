<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 09:50
 */

namespace Katniss\Models\Helpers\ExtraActions;

class ContentFilter
{
    private static $filters = [];

    private static function check($id)
    {
        if (!isset(self::$filters[$id])) {
            self::$filters[$id] = [];
        }
    }

    /**
     * @param string $id
     * @param CallableObject $callableObject
     */
    public static function add($id, CallableObject $callableObject)
    {
        if (empty($id)) return;

        self::check($id);

        self::$filters[$id][] = $callableObject;
    }

    /**
     * @param string $id
     * @param string|mixed $content
     * @return mixed
     */
    public static function flush($id, $content, array $params = [])
    {
        self::check($id);

        $filter = self::$filters[$id];
        foreach ($filter as $callableObject) {
            $callableObject->unShiftParam($content);
            $callableObject->pushParams($params);
            $content = $callableObject->execute();
        }

        return $content;
    }
}