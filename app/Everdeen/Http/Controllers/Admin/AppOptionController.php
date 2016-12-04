<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Models\AppOption;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\AppOptionHelper;
use Katniss\Everdeen\Utils\PaginationHelper;
use Katniss\Everdeen\Utils\QueryStringBuilder;
use Illuminate\Http\Request;

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
            'rdr_param' => rdrQueryParam($request->fullUrl()),
            'value_max_length' => AppConfig::TINY_SHORTEN_TEXT_LENGTH,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $appOption = AppOptionHelper::getById($id);
        if (empty($appOption)) {
            abort(404);
        }

        $this->theme->title([trans('pages.admin_app_options_title'), trans('form.action_edit')]);
        $this->theme->description(trans('pages.admin_app_options_desc'));

        return $this->_edit([
            'app_option' => $appOption,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $appOption = AppOptionHelper::getById($request->input('id'));
        if (empty($appOption)) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'raw_value' => 'required',
        ]);

        $rdr = redirect(adminUrl('app-options/{id}/edit', ['id' => $appOption->id]));

        if ($validator->fails()) {
            return $rdr->withErrors($validator);
        }

        $appOption->rawValue = $request->input('raw_value');
        $appOption->save();

        return $rdr;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $appOption = AppOptionHelper::getById($id);
        if (empty($appOption)) {
            abort(404);
        }

        $redirect_url = adminUrl('app-options');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return $appOption->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }
}
