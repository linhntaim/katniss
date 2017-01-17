<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 21:02
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class MainMenuComposer
{
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('main_menu', $this->getMenuRender($this->getMenu()));
    }

    protected function getMenuRender(Menu $menu)
    {
        $menuRender = new MenuRender();
        $menuRender->wrapClass = 'nav navbar-nav';
        return new HtmlString($menuRender->render($menu));
    }

    protected function getMenu()
    {
        $currentUrl = currentUrl();
        $menu = new Menu($currentUrl);
        $menu->add( // add a menu item
            homeUrl(),
            trans('pages.home_title'), '', '', 'hidden'
        );
        $menu->add(
            homeUrl('teachers'),
            trans_choice('label.teacher', 2)
        );
        $menu->add(
            homeUrl('knowledge'),
            trans('pages.home_knowledge_title')
        );
        $menu->add(
            '#',
            trans('wow_skype_theme.world')
        );
        $menu->add(
            homeUrl('helps'),
            trans('label.about_us')
        );
        $menu = contentFilter('main_menu', $menu);
        return $menu;
    }
}