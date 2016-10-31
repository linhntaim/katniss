<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-20
 * Time: 08:10
 */

namespace Katniss\Everdeen\Utils\ExtraActions;


class CallableObject
{
    /**
     * @var string|array
     */
    protected $definition;

    /**
     * @var array
     */
    protected $params;

    protected $tmpParams;

    /**
     * CallableObject constructor.
     * @param string|array $definition
     * @param array $params
     */
    public function __construct($definition, array $params = [])
    {
        $this->definition = $definition;
        $this->params = $params;
        $this->tmpParams = $this->params;
    }

    public function unShiftParam($param)
    {
        array_unshift($this->tmpParams, $param);
    }

    public function unShiftParams(array $params)
    {
        $this->tmpParams = array_merge($params, $this->tmpParams);
    }

    public function pushParam($param)
    {
        $this->tmpParams[] = $param;
    }

    public function pushParams(array $params)
    {
        $this->tmpParams = array_merge($this->tmpParams, $params);
    }

    public function execute()
    {
        if (!empty($this->definition)) {
            return call_user_func_array($this->definition, $this->tmpParams);
        }

        $this->tmpParams = $this->params;

        return false;
    }
}