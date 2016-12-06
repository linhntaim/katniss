<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;

class DashboardController extends ViewController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'dashboard';
    }

    public function index()
    {
        $this->_title(trans('pages.admin_dashboard_title'));
        $this->_description(trans('pages.admin_dashboard_desc'));

        return $this->_view();
    }
}
