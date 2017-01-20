<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Everdeen\Themes\Plugins\FeaturedArticles;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const NAME = 'featured_articles';
    const DISPLAY_NAME = 'Featured Articles';

    protected $articles;

    public function __init()
    {
        parent::__init();

        $this->articles = defPr($this->getProperty('articles'), []);
    }

    public function register()
    {
        enqueueThemeHeader('<style>
.widget-featured-articles ul{font-size:13px}
.widget-featured-articles ul i{font-size:20px;vertical-align:middle;margin-right:5px;margin-left:2px}
.widget-featured-articles ul a:hover span{font-weight:600}
</style>', 'widget_featured_articles');
    }

    public function viewAdminParams()
    {
        $articleRepository = new ArticleRepository();
        $articles = [];
        if (!empty($this->articles)) {
            $tmpArticles = $articleRepository->getPublishedByIds($this->articles);
            foreach ($this->articles as $article) {
                $articles[] = $tmpArticles->where('id', $article)->first();
            }
        }
        return array_merge(parent::viewAdminParams(), [
            'articles' => $articles,
        ]);
    }

    public function viewHomeParams()
    {
        $articleRepository = new ArticleRepository();
        $articles = [];
        if (!empty($this->articles)) {
            $tmpArticles = $articleRepository->getPublishedByIds($this->articles);
            foreach ($this->articles as $article) {
                $articles[] = $tmpArticles->where('id', $article)->first();
            }
        }
        return array_merge(parent::viewHomeParams(), [
            'articles' => $articles,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'articles'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'articles' => 'sometimes|array',
        ]);
    }
}