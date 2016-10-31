<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\QueryStringBuilder;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Utils\PaginationHelper;
use Illuminate\Http\Request;

use Katniss\Http\Requests;

class RoleController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'role';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->theme->title(trans('pages.admin_roles_title'));
        $this->theme->description(trans('pages.admin_roles_desc'));

        $roles = Role::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE); // 2 items per page
        $query = new QueryStringBuilder([
            'page' => $roles->currentPage()
        ], adminUrl('user-roles'));
        return $this->_list([
            'roles' => $roles,
            'query' => $query,
            'page_helper' => new PaginationHelper($roles->lastPage(), $roles->currentPage(), $roles->perPage())
        ]);
    }
}
