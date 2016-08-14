<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Http\Controllers\ViewController;
use Katniss\Models\AppOption;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\AppOptionHelper;
use Katniss\Models\Helpers\PaginationHelper;
use Katniss\Models\Helpers\QueryStringBuilder;
use Illuminate\Http\Request;
use Katniss\Http\Requests;

class AppOptionController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'app_option';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('delete')) {
            $this->destroy($request, $request->input('delete'));
        }

        $this->theme->title(trans('pages.admin_app_options_title'));
        $this->theme->description(trans('pages.admin_app_options_desc'));

        $options = AppOption::paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
        $query = new QueryStringBuilder([
            'page' => $options->currentPage(),
            'delete' => null,
        ], adminUrl('app-options'));
        return $this->_list([
            'options' => $options,
            'query' => $query,
            'page_helper' => new PaginationHelper($options->lastPage(), $options->currentPage(), $options->perPage()),
            'value_max_length' => AppConfig::MEDIUM_SHORTEN_TEXT_LENGTH,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $key)
    {
        $option = AppOption::where('key', $key)->firstOrFail();

        $redirect_url = adminUrl('app-options');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return $option->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }
}
