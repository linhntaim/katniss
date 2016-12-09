<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 18:36
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'poll_widget';
    const DISPLAY_NAME = 'Poll';

    public function render()
    {
        return $this->renderByTemplate();
    }
}