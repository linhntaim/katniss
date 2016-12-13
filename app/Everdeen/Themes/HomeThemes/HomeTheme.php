<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes\HomeThemes;


use Katniss\Everdeen\Themes\ExtensionsFacade;
use Katniss\Everdeen\Themes\JsQueue;
use Katniss\Everdeen\Themes\Theme;
use Katniss\Everdeen\Themes\WidgetsFacade;

abstract class HomeTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(Theme::TYPE_HOME);
    }

    public function mockAdmin()
    {
    }

    public function plugin($name, $render)
    {
        return $this->viewPath . 'plugins.' . $name . '.' . $render;
    }

    public function adminWidget($name, $render = 'admin')
    {
        if (empty($render)) {
            $render = 'admin';
        }
        return $this->plugin($name, $render);
    }

    public function widget($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->plugin($name, $render);
    }

    public function adminExtension($name, $render = 'admin')
    {
        if (empty($render)) {
            $render = 'admin';
        }
        return $this->plugin($name, $render);
    }

    public function extension($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->plugin($name, $render);
    }

    public function commonPlugin($name, $render)
    {
        return 'plugins.' . $name . '.' . $render;
    }

    public function commonAdminWidget($name, $render = 'admin')
    {
        if (empty($render)) {
            $render = 'admin';
        }
        return $this->commonPlugin($name, $render);
    }

    public function commonWidget($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->commonPlugin($name, $render);
    }

    public function commonAdminExtension($name, $render = 'admin')
    {
        if (empty($render)) {
            $render = 'admin';
        }
        return $this->commonPlugin($name, $render);
    }

    public function commonExtension($name, $render = 'render')
    {
        if (empty($render)) {
            $render = 'render';
        }
        return $this->commonPlugin($name, $render);
    }

    protected function registerWidgets($is_auth = false)
    {
        parent::registerWidgets();

        // Home theme need to register widgets for rendering
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

    public function pageTemplates()
    {
        return [];
    }

    public function pageTemplateView($pageTemplateName, $default = 'page.show')
    {
        if (!empty($pageTemplateName) && view()->exists($this->page($pageTemplateName))) {
            return $pageTemplateName;
        }
        return $default;
    }

    public function articleTemplates()
    {
        return [];
    }

    public function articleTemplateView($articleTemplateName, $default = 'article.show')
    {
        if (!empty($articleTemplateName) && view()->exists($this->page($articleTemplateName))) {
            return $articleTemplateName;
        }
        return $default;
    }

    protected function registerExtScripts($is_auth = false)
    {
        parent::registerExtScripts($is_auth);

        $this->extJsQueue->add('global_vars', [
            'KATNISS_USER_REQUIRED' => 'true',
        ], JsQueue::TYPE_VAR, ['KATNISS_USER_REQUIRED'], true);
        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.home.js'));
    }
}