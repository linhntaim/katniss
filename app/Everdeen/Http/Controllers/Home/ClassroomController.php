<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-11
 * Time: 09:25
 */

namespace Katniss\Everdeen\Http\Controllers\Home;


use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomController extends ViewController
{
    protected $classRoomRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'classroom';
        $this->classRoomRepository = new ClassroomRepository();
    }

    public function indexOpening(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {

        } elseif ($user->hasRole('student')) {

        } elseif ($user->hasRole('supporter')) {

        } else {
            abort(404);
            die();
        }

        return $this->_any('index_opening', [

        ]);
    }

    public function indexClosed(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {

        } elseif ($user->hasRole('student')) {

        } elseif ($user->hasRole('supporter')) {

        } else {
            abort(404);
            die();
        }

        return $this->_any('index_closed', [

        ]);
    }

    public function show(Request $request, $id)
    {
        $classroom = $this->classRoomRepository->model($id);
        $user = $request->authUser();
        $isOwner = false;
        $canClassroomEdit = false;
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                abort(404);
            }
            $isOwner = true;
            $canClassroomEdit = true;
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                abort(404);
            }
            $isOwner = true;
        } elseif ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                abort(404);
            }
            $isOwner = true;
        } elseif ($user->hasRole(['manager', 'admin'])) {
            $canClassroomEdit = true;
        }

        $lastMonthClassTimes = $classroom->classTimesOfLastMonth;
        $countLastMonthClassTimes = $lastMonthClassTimes->count();
        $hasPreviousMonthClassTimes = false;
        $previousYear = false;
        $previousMonth = false;
        if ($countLastMonthClassTimes > 0) {
            $datetime = new \DateTime($lastMonthClassTimes[0]->start_at);
            $datetime->sub(new \DateInterval('P1M'));
            $previousYear = $datetime->format('Y');
            $previousMonth = $datetime->format('m');
            $hasPreviousMonthClassTimes = $classroom->getCountClassTimesOfMonth($previousYear, $previousMonth) > 0;
        }
        $countAllClassTimes = $classroom->classTimes()->count();

        return $this->_show([
            'classrooms_url' => $isOwner ?
                homeUrl('classrooms') : ($classroom->isOpening ?
                    adminUrl('opening-classrooms') : adminUrl('closed-classrooms')),
            'classroom' => $classroom,
            'class_times' => $lastMonthClassTimes->sortBy('start_at'), // need sorted again
            'class_time_order_start' => $countAllClassTimes - $countLastMonthClassTimes + 1,
            'has_previous_month_class_times' => $hasPreviousMonthClassTimes,
            'previous_year' => $previousYear,
            'previous_month' => $previousMonth,
            'can_classroom_edit' => $canClassroomEdit,
            'teacher' => $classroom->teacherProfile,
            'student' => $classroom->studentProfile,
            'supporter' => $classroom->supporter,
            'date_js_format' => DateTimeHelper::compoundJsFormat('shortDate', ' ', 'shortTime'),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
        ]);
    }
}