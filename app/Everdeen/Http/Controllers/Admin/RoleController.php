<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\RoleRepository;
use Katniss\Everdeen\Utils\QueryStringBuilder;
use Katniss\Everdeen\Utils\PaginationHelper;

class RoleController extends ViewController
{
    protected $roleRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'role';
        $this->roleRepository = new RoleRepository($request->input('id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->theme->title(trans('pages.admin_roles_title'));
        $this->theme->description(trans('pages.admin_roles_desc'));

        $roles = $this->roleRepository->getPaged();
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
