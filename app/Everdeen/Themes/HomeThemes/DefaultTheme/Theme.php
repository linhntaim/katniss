<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 02:53
 */

namespace Katniss\Everdeen\Themes\HomeThemes\DefaultTheme;


use Katniss\Everdeen\Themes\CssQueue;
use Katniss\Everdeen\Themes\HomeThemes\HomeTheme;
use Katniss\Everdeen\Themes\JsQueue;

class Theme extends HomeTheme
{
    const NAME = 'Default';
    const VIEW = 'default';

    public function __construct()
    {
        parent::__construct();
    }

    public function register($is_auth = false)
    {
        parent::register($is_auth);
    }

    protected function registerComposers($is_auth = false)
    {
        view()->composer(
            $this->masterPath('index'), Composers\MainMenuComposer::class
        );
    }

    protected function registerLibStyles($is_auth = false)
    {
        parent::registerLibStyles($is_auth);

        $this->libCssQueue->add(CssQueue::LIB_BOOTSTRAP_NAME, $this->cssAsset('bootstrap.min.css'));
    }

    protected function registerExtStyles($is_auth = false)
    {
        $this->extCssQueue->add('theme-style', $this->cssAsset('scrolling-nav.css'));

        parent::registerExtStyles($is_auth);
    }

    protected function registerLibScripts($is_auth = false)
    {
        parent::registerLibScripts($is_auth);

        $this->libJsQueue->add(JsQueue::LIB_JQUERY_NAME, $this->jsAsset('jquery.js'));
        $this->libJsQueue->add(JsQueue::LIB_BOOTSTRAP_NAME, $this->jsAsset('bootstrap.min.js'));
        $this->libJsQueue->add('jquery-easing', $this->jsAsset('jquery.easing.min.js'));
    }

    protected function registerExtScripts($is_auth = false)
    {
        $this->extJsQueue->add('theme-script', $this->jsAsset('scrolling-nav.js'));

        parent::registerExtScripts($is_auth);
    }

    public function extensions()
    {
        return [
            // define extension here: extension name => extension class
        ];
    }

    public function widgets()
    {
        return [
            // define widget here: widget name => widget class
        ];
    }

    public function placeholders()
    {
        return [
            'default_placeholder' => 'Default Placeholder'
        ];
    }
}