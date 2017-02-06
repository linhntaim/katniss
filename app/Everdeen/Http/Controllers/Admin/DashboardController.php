<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Repositories\ArticleRepository;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Repositories\RegisterLearningRequestRepository;
use Katniss\Everdeen\Repositories\StudentRepository;
use Katniss\Everdeen\Repositories\TeacherRepository;

class DashboardController extends AdminController
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
