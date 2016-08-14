<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Models\Category;
use Katniss\Models\Link;
use Illuminate\Http\Request;
use Katniss\Http\Requests;
use Katniss\Http\Controllers\MultipleLocaleContentController;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\PaginationHelper;
use Katniss\Models\Helpers\QueryStringBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LinkController extends MultipleLocaleContentController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'link';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->theme->title(trans('pages.admin_links_title'));
        $this->theme->description(trans('pages.admin_links_desc'));

        $links = Link::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);

        $query = new QueryStringBuilder([
            'page' => $links->currentPage()
        ], adminUrl('links'));
        return $this->_list([
            'links' => $links,
            'query' => $query,
            'page_helper' => new PaginationHelper($links->lastPage(), $links->currentPage(), $links->perPage()),
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
        $this->theme->title([trans('pages.admin_links_title'), trans('form.action_add')]);
        $this->theme->description(trans('pages.admin_links_desc'));

        return $this->_add([
            'categories' => Category::where('type', Category::LINK)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categories = $request->input('categories', []);
        $image = $request->input('image', '');

        $this->validateMultipleLocaleData($request, ['name', 'url', 'description'], [
            'name' => 'required',
            'url' => 'required', // no need to confirm link is an url, for trickly use
        ], $data, $successes, $fails, $old);

        $error_redirect = redirect(adminUrl('links/add'))
            ->withInput(array_merge([
                'categories' => $categories,
                'image' => $image,
            ], $old));

        if (count($successes) <= 0 && count($fails) > 0) {
            return $error_redirect->withErrors($fails[0]);
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'required|exists:categories,id,type,' . Category::LINK,
            'image' => 'sometimes|url',
        ]);
        if ($validator->fails()) {
            return $error_redirect->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $link = new Link();
            $link->image = $image;
            foreach ($successes as $locale) {
                $transData = $data[$locale];
                $trans = $link->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
                $trans->url = $transData['url'];
            }
            $link->save();
            if (count($categories) > 0) {
                $link->categories()->attach($categories);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return $error_redirect->withErrors([trans('error.database_insert') . ' (' . $e->getMessage() . ')']);
        }

        return redirect(adminUrl('links'));
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
        $link = Link::findOrFail($id);

        $this->theme->title([trans('pages.admin_links_title'), trans('form.action_edit')]);
        $this->theme->description(trans('pages.admin_links_desc'));

        return $this->_edit([
            'link' => $link,
            'link_categories' => $link->categories,
            'categories' => Category::where('type', Category::LINK)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $link = Link::findOrFail($request->input('id'));
        $categories = $request->input('categories', []);
        $image = $request->input('image', '');

        $redirect = redirect(adminUrl('links/{id}/edit', ['id' => $link->id]));

        $this->validateMultipleLocaleData($request, ['name', 'url', 'description'], [
            'name' => 'required',
            'url' => 'required',
        ], $data, $successes, $fails, $old);

        if (count($successes) <= 0 && count($fails) > 0) {
            return $redirect->withErrors($fails[0]);
        }

        $validator = Validator::make($request->all(), [
            'categories' => 'required|exists:categories,id,type,' . Category::LINK,
            'image' => 'sometimes|url',
        ]);
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $link->image = $image;
            foreach ($successes as $locale) {
                $transData = $data[$locale];
                $trans = $link->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
                $trans->url = $transData['url'];
            }
            $link->save();
            if (count($categories) > 0) {
                $link->categories()->sync($categories);
            } else {
                $link->categories()->detach();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $redirect->withErrors([trans('error.database_insert') . ' (' . $e->getMessage() . ')']);
        }
        return $redirect;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $redirect_url = adminUrl('links');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }
        return $link->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }
}
