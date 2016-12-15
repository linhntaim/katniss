<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 09:32
 */

namespace Katniss\Everdeen\Themes;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;

class Widgets
{
    private $themeWidgets;
    private $defines;

    public function __construct()
    {
    }

    public function init()
    {
        $this->defines = array_merge(_kWidgets(), HomeThemeFacade::widgets());
        $widgetRepository = new ThemeWidgetRepository();
        $this->themeWidgets = $widgetRepository->getActive(array_keys($this->defines));
    }

    public function display($placeholder, $before = '', $after = '', $default = '')
    {
        $themeWidgets = $this->themeWidgets->where('placeholder', $placeholder)->sortBy('order');
        $countThemeWidgets = $themeWidgets->count();
        $output = $countThemeWidgets > 0 ? $before : $default;
        foreach ($themeWidgets as $themeWidget) {
            $output .= $themeWidget->render();
        }
        return new HtmlString($countThemeWidgets > 0 ? $output . $after : $output);
    }

    public function register()
    {
        foreach ($this->themeWidgets as $themeWidget) {
            $themeWidget->register();
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