<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 16:19
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype;


use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\HomeThemes\HomeTheme;
use Katniss\Everdeen\Themes\HomeThemes\WowSkype\Controllers\ThemeAdminController;
use Katniss\Everdeen\Themes\Queue\CssQueue;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Theme extends HomeTheme
{
    const NAME = 'wow_skype';
    const DISPLAY_NAME = 'Wow Skype';
    const VIEW = 'wow_skype';

    public function __construct()
    {
        parent::__construct();
    }

    public function mockAdmin()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add(  // add an example menu item which have sub menu
                    '#',
                    trans('wow_skype_theme.page_theme_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span> <i class="fa fa-angle-left pull-right"></i>', 'treeview'
                );
                $subMenu = new Menu(currentFullUrl());
                $subMenu->add( // add a menu item
                    addExtraUrl('admin/themes/wow_skype/options', adminUrl('extra')),
                    trans('wow_skype_theme.page_options_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
                $menu->addSubMenu($subMenu);
            }
            return $menu;
        }), 'theme:wow_skype:menu');
        $controllerClass = ThemeAdminController::class;
        addTrigger('extra_route', new CallableObject(function (Request $request) use ($controllerClass) {
            $controller = new $controllerClass;
            switch (strtolower($request->method())) {
                case 'get':
                    return $controller->options($request);
                case 'put':
                    return $controller->updateOptions($request);
            }
            return '';
        }), 'admin/themes/wow_skype/options');
    }

    public function register($isAuth = false)
    {
        parent::register($isAuth);

        $knowledgeCoverImage = $this->options('knowledge_cover_image', '');
        if (!empty($knowledgeCoverImage)) {
            addPlace('knowledge_cover', new CallableObject(function () use ($knowledgeCoverImage) {
                return '<div class="image-cover height-500" style="background-image: url(' . $knowledgeCoverImage . ')"></div>';
            }), 'knowledge_cover_image');
        }
    }

    protected function registerComposers($is_auth = false)
    {
        view()->composer(
            $this->masterPath('header_nav_full'), Composers\MainMenuComposer::class
        );
        view()->composer(
            $this->masterPath('profile'), Composers\ProfileMenuComposer::class
        );
    }

    protected function registerLibStyles($is_auth = false)
    {
        parent::registerLibStyles($is_auth);

        $this->libCssQueue->add(CssQueue::LIB_OPEN_SANS_NAME, _kExternalLink(CssQueue::LIB_OPEN_SANS_NAME));
        $this->libCssQueue->add(CssQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(CssQueue::LIB_BOOTSTRAP_NAME));
        $this->libCssQueue->add(CssQueue::LIB_FONT_AWESOME_NAME, _kExternalLink(CssQueue::LIB_FONT_AWESOME_NAME));
    }

    protected function registerExtStyles($is_auth = false)
    {
        $this->extCssQueue->add('theme-style', $this->cssAsset('style.css'));

        parent::registerExtStyles($is_auth);
    }

    protected function registerLibScripts($is_auth = false)
    {
        parent::registerLibScripts($is_auth);

        $this->libJsQueue->add(JsQueue::LIB_JQUERY_NAME, _kExternalLink(JsQueue::LIB_JQUERY_NAME));
        $this->libJsQueue->add(JsQueue::LIB_BOOTSTRAP_NAME, _kExternalLink(JsQueue::LIB_BOOTSTRAP_NAME));
    }

    protected function registerExtScripts($is_auth = false)
    {
        $this->extJsQueue->add('theme-script', $this->jsAsset('script.js'));

        parent::registerExtScripts($is_auth);
    }

    public function extensions()
    {
        return [
            // define extension here: extension name => extension class
        ];
    }

    public function placeholders()
    {
        return [
            'knowledge_middle' => 'Middle of Knowledge Page',
            'knowledge_bottom' => 'Bottom of Knowledge Page',
        ];
    }

    public function widgets()
    {
        return [
            // define widget here: widget name => widget class
        ];
    }
}