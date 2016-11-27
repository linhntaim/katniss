<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\QueryStringBuilder;
use Katniss\Everdeen\Utils\PaginationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class LinkCategoryController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'link_category';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->theme->title(trans('pages.admin_link_categories_title'));
        $this->theme->description(trans('pages.admin_link_categories_desc'));

        $categories = Category::where('type', Category::LINK)->orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);

        $query = new QueryStringBuilder([
            'page' => $categories->currentPage()
        ], adminUrl('link-categories'));
        return $this->_list([
            'categories' => $categories,
            'query' => $query,
            'page_helper' => new PaginationHelper($categories->lastPage(), $categories->currentPage(), $categories->perPage()),
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->theme->title([trans('pages.admin_link_categories_title'), trans('form.action_add')]);
        $this->theme->description(trans('pages.admin_link_categories_desc'));

        return $this->_add([
            'categories' => Category::where('type', Category::LINK)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateMultipleLocaleData($request, ['name', 'slug', 'description'], [
            'name' => 'required',
            'slug' => 'required|unique:category_translations,slug',
        ], $data, $successes, $fails);

        $error_redirect = redirect(adminUrl('link-categories/add'))
            ->withInput();

        if (count($successes) <= 0 && count($fails) > 0) {
            return $error_redirect->withErrors($fails[0]);
        }

        $parent_id = intval($request->input('parent'), 0);
        if ($parent_id != 0) {
            $validator = Validator::make($request->all(), [
                'parent' => 'sometimes|exists:categories,id,type,' . Category::LINK,
            ]);
            if ($validator->fails()) {
                return $error_redirect->withErrors($validator);
            }
        }

        $category = new Category();
        $category->type = Category::LINK;
        if ($parent_id != 0) {
            $category->parent_id = $parent_id;
        }
        foreach ($successes as $locale) {
            $transData = $data[$locale];
            $trans = $category->translateOrNew($locale);
            $trans->name = $transData['name'];
            $trans->slug = $transData['slug'];
            $trans->description = $transData['description'];
        }
        if ($category->save() === false) {
            return $error_redirect->withErrors([trans('error.database_insert')]);
        }

        return redirect(adminUrl('link-categories'));
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($category->type != Category::LINK) {
            abort(404);
        }

        $this->theme->title([trans('pages.admin_link_categories_title'), trans('form.action_edit')]);
        $this->theme->description(trans('pages.admin_link_categories_desc'));

        return $this->_edit([
            'category' => $category,
            'categories' => Category::where('type', Category::LINK)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $category = Category::findOrFail($request->input('id'));
        if ($category->type != Category::LINK) {
            abort(404);
        }

        $redirect = redirect(adminUrl('link-categories/{id}/edit', ['id' => $category->id]));

        $this->validateMultipleLocaleData($request, ['name', 'slug', 'description'], [
            'name' => 'required',
            'slug' => 'required|unique:category_translations,slug,' . $category->id . ',category_id',
        ], $data, $successes, $fails);

        if (count($successes) <= 0 && count($fails) > 0) {
            return $redirect->withErrors($fails[0]);
        }

        $parent_id = intval($request->input('parent'), 0);
        if ($parent_id != 0) {
            $validator = Validator::make($request->all(), [
                'parent' => 'sometimes|exists:categories,id,type,' . Category::LINK
            ]);
            if ($validator->fails()) {
                return $redirect->withErrors($validator);
            }
        }
        $category->parent_id = $parent_id != 0 && $parent_id !== $category->parent_id ? $parent_id : null;
        foreach ($successes as $locale) {
            $transData = $data[$locale];
            $trans = $category->translateOrNew($locale);
            $trans->name = $transData['name'];
            $trans->slug = $transData['slug'];
            $trans->description = $transData['description'];
        }
        if ($category->save() === false) {
            return $redirect->withErrors([trans('error.database_update')]);
        }

        return redirect(adminUrl('link-categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::where('type', Category::LINK)->where('id', $id)->firstOrFail();
        if ($category->type != Category::LINK) {
            abort(404);
        }

        $redirect_url = adminUrl('link-categories');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        if ($category->links()->count() <= 0) {
            $success = true;
            DB::beginTransaction();
            try {
                Category::where('parent_id', $id)->update(['parent_id' => null]);
                $category->delete();
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                $success = false;
            }

            return $success ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
        }

        return redirect($redirect_url)->withErrors([trans('error.category_not_empty')]);
    }

    public function layoutSort(Request $request, $id)
    {
        $category = Category::where('type', Category::LINK)->where('id', $id)->firstOrFail();

        $this->theme->title([trans('pages.admin_link_categories_title'), trans('form.action_sort')]);
        $this->theme->description(trans('pages.admin_link_categories_desc'));

        return $this->_any('sort', [
            'category' => $category,
            'links' => $category->orderedLinks,
        ]);
    }
}
