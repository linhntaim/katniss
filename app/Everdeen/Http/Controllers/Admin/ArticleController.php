<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Utils\PaginationHelper;
use Katniss\Everdeen\Utils\QueryStringBuilder;

class ArticleController extends ViewController
{
    private $articleRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'article';
        $this->articleRepository = new ArticleRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->_title(trans('pages.admin_articles_title'));
        $this->_description(trans('pages.admin_articles_desc'));

        $articles = $this->articleRepository->getPaged();

        $query = new QueryStringBuilder([
            'page' => $articles->currentPage()
        ], adminUrl('articles'));
        return $this->_index([
            'articles' => $articles,
            'query' => $query,
            'page_helper' => new PaginationHelper($articles->lastPage(), $articles->currentPage(), $articles->perPage()),
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $articleCategoryRepository = new ArticleCategoryRepository();

        $this->_title([trans('pages.admin_articles_title'), trans('form.action_add')]);
        $this->_description(trans('pages.admin_articles_desc'));

        return $this->_create([
            'categories' => $articleCategoryRepository->getExceptDefault(),
            'templates' => HomeThemeFacade::articleTemplates(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:post_translations,slug',
            'description' => 'sometimes|max:255',
        ]);

        $error_redirect = redirect(adminUrl('articles/create'))
            ->withInput();

        if ($validateResult->isFailed()) {
            return $error_redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'sometimes|exists:categories,id,type,' . Category::ARTICLE,
            'featured_image' => 'sometimes|url',
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        try {
            $this->articleRepository->create(
                $this->authUser->id,
                $request->input('template', ''),
                $request->input('featured_image', ''),
                $validateResult->getLocalizedInputs(),
                $request->input('categories', [])
            );
        } catch (KatnissException $ex) {
            return $error_redirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('articles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $article = $this->articleRepository->model($id);
        $articleCategoryRepository = new ArticleCategoryRepository();

        $this->_title([trans('pages.admin_articles_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_articles_desc'));

        return $this->_edit([
            'article' => $article,
            'article_categories' => $article->categories,
            'categories' => $articleCategoryRepository->getExceptDefault(),
            'templates' => HomeThemeFacade::articleTemplates(),
            'rdr_param' => errorRdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $page = $this->articleRepository->model($id);

        $redirect = redirect(adminUrl('articles/{id}/edit', ['id' => $page->id]));

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:post_translations,slug,' . $page->id . ',post_id',
            'description' => 'sometimes|max:255',
        ]);

        if ($validateResult->isFailed()) {
            return $redirect->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'sometimes|exists:categories,id,type,' . Category::ARTICLE,
            'featured_image' => 'sometimes|url',
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->articleRepository->update(
                $this->authUser->id,
                $request->input('template', ''),
                $request->input('featured_image', ''),
                $validateResult->getLocalizedInputs(),
                $request->input('categories', [])
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }
        return $redirect;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->articleRepository->model($id);

        $this->_rdrUrl($request, adminUrl('articles'), $rdrUrl, $errorRdrUrl);

        try {
            $this->articleRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
