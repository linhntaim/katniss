<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Models\Themes\HomeThemes;


use Katniss\Models\Themes\ExtensionsFacade;
use Katniss\Models\Themes\Theme;
use Katniss\Models\Themes\WidgetsFacade;

abstract class HomeTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(Theme::TYPE_HOME);
    }

    public function widget($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->viewPath . 'widgets.' . $name . '.' . $render;
    }

    public function commonWidget($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return 'widgets.' . $name . '.' . $render;
    }

    public function adminWidget($name)
    {
        return $this->widget($name, 'admin');
    }

    public function commonAdminWidget($name)
    {
        return $this->commonWidget($name, 'admin');
    }

    public function extension($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->viewPath . 'extensions.' . $name . '.' . $render;
    }

    public function commonExtension($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return 'extensions.' . $name . '.' . $render;
    }

    public function adminExtension($name)
    {
        return $this->extension($name, 'admin');
    }

    public function commonAdminExtension($name)
    {
        return $this->commonExtension($name, 'admin');
    }

    public function register($is_auth = false)
    {
        parent::register($is_auth);

        $this->registerExtensions($is_auth);
        $this->registerWidgets($is_auth);
    }

    protected function registerExtensions($is_auth = false)
    {
        ExtensionsFacade::register();
    }

    protected function registerWidgets($is_auth = false)
    {
        WidgetsFacade::register();
    }

    public function extensions()
    {
        return [];
    }

    public function placeholders()
    {
        return [];
    }

    public function widgets()
    {
        return [];
    }
}