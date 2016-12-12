<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 18:36
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'contact_form_widget';
    const DISPLAY_NAME = 'Contact Form';

    public function render()
    {
        return $this->renderByTemplate();
    }
}