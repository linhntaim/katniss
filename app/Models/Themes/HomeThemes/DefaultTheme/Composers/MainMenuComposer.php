<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-09
 * Time: 21:02
 */

namespace Katniss\Models\Themes\HomeThemes\DefaultTheme\Composers;

use Illuminate\Contracts\View\View;
use Katniss\Models\Helpers\Menu;
use Katniss\Models\Helpers\MenuItem;

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
            '#page-top',
            trans('pages.home_title'), 'li', 'hidden', 'page-scroll'
        ));
        $menu = content_filter('main_menu', $menu);
        $view->with('main_menu', $menu);
    }
}