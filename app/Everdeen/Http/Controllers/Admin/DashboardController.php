<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Illuminate\Http\Request;

class DashboardController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'dashboard';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->theme->title(trans('pages.admin_dashboard_title'));
        $this->theme->description(trans('pages.admin_dashboard_desc'));

        return $this->_view();
    }
}
