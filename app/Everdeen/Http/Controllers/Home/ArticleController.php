<?php

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\DataStructure\Menu\MenuRender;

class ArticleController extends ViewController
{
    protected $categoryRepository;
    protected $articleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'article';
        $this->categoryRepository = new ArticleCategoryRepository();
        $this->articleRepository = new ArticleRepository();
    }

    public function index(Request $request)
    {
        $articles = $this->articleRepository->getSearchPublishedPaged();

        $this->_title(trans('pages.home_knowledge_title'));
        $this->_description(trans('pages.home_knowledge_desc'));

        return $this->_index([
            'articles' => $articles,
            'pagination' => $this->paginationRender->renderByPagedModels($articles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function show(Request $request, $slug)
    {
        $article = $this->articleRepository->getBySlug($slug);

        $this->_title($article->title);
        $this->_description(htmlShorten($article->content));

        return $this->_show([
            'article' => $article,
        ]);
    }

    public function showById(Request $request, $id)
    {
        $article = $this->articleRepository->getById($id);

        $this->_title($article->title);
        $this->_description(htmlShorten($article->content));

        return $this->_show([
            'article' => $article,
        ]);
    }

    public function showCategory(Request $request, $slug)
    {
        $articles = $this->articleRepository->getPublishedPagedByCategorySlug($slug, $category);

        $this->_title([trans_choice('label.category', 1), $category->name]);
        $this->_description($category->description);

        return $this->_index([
            'articles' => $articles,
            'pagination' => $this->paginationRender->renderByPagedModels($articles),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'category' => $category,
        ]);
    }
}
