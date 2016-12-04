<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:58
 */

namespace Katniss\Everdeen\Repositories;


abstract class ModelRepository
{
    protected $model;

    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->model = $this->getById($id);
        }
    }

    public function model($id = null)
    {
        if (!empty($id)) {
            $this->model = $this->getById($id);
        }
        return $this->model;
    }

    public abstract function getById($id);
}