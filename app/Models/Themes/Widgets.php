<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 09:32
 */

namespace Katniss\Models\Themes;


use Katniss\Models\Plugins\BaseLinks\Widget as BaseLinks;
use Katniss\Models\Plugins\ExtraHtml\Widget as ExtraHtml;
use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;

class Widgets
{
    private $widgets;
    private $defines;

    public function __construct()
    {
        $this->defines = array_merge(config('katniss.widgets'), HomeThemeFacade::widgets());
        $this->widgets = ThemeWidget::forDisplay()->get();
    }

    public function display($placeholder, $before = '', $after = '')
    {
        $widgets = $this->widgets->where('placeholder', $placeholder);
        $count_widgets = $widgets->count();
        $output = $count_widgets > 0 ? $before : '';
        foreach ($widgets as $widget) {
            $output .= $widget->render();
        }
        return $count_widgets > 0 ? $output . $after : $output;
    }

    public function register()
    {
        foreach ($this->widgets as $widget) {
            $widget->register();
        }
    }

    public function all()
    {
        return $this->defines;
    }

    public function widgetClass($name)
    {
        return empty($this->defines[$name]) ? null : $this->defines[$name];
    }
}