<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\HelpCategoryRepository;
use Katniss\Everdeen\Repositories\HelpRepository;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class HelpController extends ViewController
{
    protected $categoryRepository;
    protected $helpRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'help';
        $this->categoryRepository = new HelpCategoryRepository();
        $this->helpRepository = new HelpRepository();
    }

    public function index(Request $request)
    {
        return $this->show($request, null);
    }

    public function show(Request $request, $slug)
    {
        $emptySlug = empty($slug);
        $categories = $this->categoryRepository->getAll();
        $currentHelp = null;
        $categoriesMenu = null;
        if ($categories->count() > 0) {
            $categoriesMenu = new Menu($request->url());
            foreach ($categories as $category) {
                $categoriesMenu->add(
                    '#',
                    $category->name
                );
                $helps = $category->orderedPosts;
                if ($helps->count() > 0) {
                    if ($emptySlug) {
                        $currentHelp = $helps->first();
                    }
                    $subMenu = new Menu($request->url());
                    foreach ($helps as $help) {
                        if (!$emptySlug && $help->slug == $slug) {
                            $currentHelp = $help;
                        }
                        $subMenu->add( // add a menu item
                            homeUrl('helps/{slug}', ['slug' => $help->slug]),
                            $help->title
                        );
                    }
                }
            }
        }

        if (!empty($categoriesMenu)) {
            $menuRender = new MenuRender();
            $menuRender->wrapClass = 'help-categories-menu';
            $menuRender->childrenWrapClass = 'helps-menu';
            $categoriesMenu = new HtmlString($menuRender->render($categoriesMenu));
        }

        $this->_title($currentHelp->title);
        $this->_description(htmlShorten($currentHelp->content));

        return $this->_show([
            'categories' => $categories,
            'help' => $currentHelp,
            'categories_menu' => $categoriesMenu,
        ]);
    }
}
