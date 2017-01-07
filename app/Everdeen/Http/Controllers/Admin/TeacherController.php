<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\TeacherRepository;

class TeacherController extends AdminController
{
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->teacherRepository = new TeacherRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $teachers = $this->teacherRepository->getPaged();

        $this->_title(trans('pages.admin_teachers_title'));
        $this->_description(trans('pages.admin_teachers_desc'));

        return $this->_index([
            'teachers' => $teachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }
}
