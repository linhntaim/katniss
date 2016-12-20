<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes\HomeThemes;

use Katniss\Everdeen\Themes\Queue\JsQueue;
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

    protected function registerWidgets($is_auth = false)
    {
        WidgetsFacade::init();
        WidgetsFacade::register();
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
            'KATNISS_USER_REQUIRED' => 'false',
        ], JsQueue::TYPE_VAR, ['KATNISS_USER_REQUIRED'], true);
        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.home.js'));
    }
}