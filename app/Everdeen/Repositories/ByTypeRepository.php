<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 22:46
 */

namespace Katniss\Everdeen\Repositories;


abstract class ByTypeRepository extends ModelRepository
{
    protected $type;

    public function __construct($type, $id = null)
    {
        $this->type = $type;

        parent::__construct($id);
    }
}