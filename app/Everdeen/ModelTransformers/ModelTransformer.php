<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2018-08-01
 * Time: 09:26
 */

namespace Katniss\Everdeen\ModelTransformers;


abstract class ModelTransformer
{
    protected $model;
    protected $utils;

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setUtils($utils = [])
    {
        $this->utils = $utils;
        return $this;
    }

    public abstract function toArray();
}