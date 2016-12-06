<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;

class DashboardController extends ViewController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'dashboard';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->_title(trans('pages.admin_dashboard_title'));
        $this->_description(trans('pages.admin_dashboard_desc'));

        return $this->_view();
    }
}
