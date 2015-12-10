<?php

namespace Katniss\Events;

abstract class Event
{
    public $params;
    public $locale;

    public function __construct(array $params = [], $locale = null)
    {
        $this->params = $params;
    }
}
