<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 21:02
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Composers;

use Illuminate\Contracts\View\View;
use Katniss\Everdeen\Utils\Menu;
use Katniss\Everdeen\Utils\MenuItem;

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
        $menu = new Menu('ul', 'nav navbar-nav');
        $menu->addItem(new MenuItem( // add a menu item
            homeUrl(),
            trans('pages.home_title'), 'li', 'hidden'
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/social-sharing'),
            trans('example_theme.social_sharing'), 'li', null
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/facebook-comments'),
            trans('example_theme.facebook_comment'), 'li', null
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/widgets'),
            trans('example_theme.example_widget'), 'li', null
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/my-settings'),
            trans('pages.my_settings_title'), 'li', null
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/pages'),
            trans_choice('label.page', 2), 'li', null
        ));
        $menu->addItem(new MenuItem(
            homeUrl('example/articles'),
            trans_choice('label.article', 2), 'li', null
        ));
        $menu = content_filter('main_menu', $menu);
        $view->with('main_menu', $menu);
    }
}