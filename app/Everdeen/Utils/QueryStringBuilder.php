<?php

namespace Katniss\Everdeen\Utils;


class QueryStringBuilder
{
    private $params;
    private $url;
    private $tmpParams;

    /**
     * @param array $params
     * @param string $url
     */
    public function __construct($params, $url = '')
    {
        $this->url = $url;
        $this->params = $params;
        $this->tmpParams = $params;
    }

    /**
     * @return QueryStringBuilder
     */
    public function update($key, $value, $refresh = false)
    {
        if ($refresh) {
            $this->tmpParams = $this->params;
        }

        if (isset($this->tmpParams[$key]) || $this->tmpParams[$key] == null) {
            $this->tmpParams[$key] = $value;
        }

        return $this;
    }

    public function toString($refresh = true)
    {
        $query = '';
        foreach ($this->tmpParams as $key => $value) {
            if (!empty($value) || $value == 0) {
                $query .= '&' . $key . '=' . $value;
            }
        }
        if ($refresh) {
            $this->tmpParams = $this->params;
        }
        return $this->url . '?' . substr($query, 1);
    }
}
