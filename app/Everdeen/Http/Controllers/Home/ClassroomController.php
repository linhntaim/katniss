<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-11
 * Time: 09:25
 */

namespace Katniss\Everdeen\Http\Controllers\Home;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomController extends ViewController
{
    protected $classroomRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'classroom';
        $this->classroomRepository = new ClassroomRepository();
    }

    public function indexOpening(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            $classrooms = $this->classroomRepository->getByTeacherPaged($user->id);
        } elseif ($user->hasRole('student')) {
            $classrooms = $this->classroomRepository->getByStudentPaged($user->id);
        } elseif ($user->hasRole('supporter')) {
            $classrooms = $this->classroomRepository->getBySupporterPaged($user->id);
        } else {
            abort(404);
            die();
        }

        return $this->_any('index_opening', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function indexClosed(Request $request)
    {
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            $classrooms = $this->classroomRepository->getByTeacherPaged($user->id, Classroom::STATUS_CLOSED);
        } elseif ($user->hasRole('student')) {
            $classrooms = $this->classroomRepository->getByStudentPaged($user->id, Classroom::STATUS_CLOSED);
        } elseif ($user->hasRole('supporter')) {
            $classrooms = $this->classroomRepository->getBySupporterPaged($user->id, Classroom::STATUS_CLOSED);
        } else {
            abort(404);
            die();
        }

        return $this->_any('index_closed', [
            'classrooms' => $classrooms,
            'pagination' => $this->paginationRender->renderByPagedModels($classrooms),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
        ]);
    }

    public function show(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        $isOwner = false;
        $canClassroomEdit = false;
        $userCanCloseClassroom = false;
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
            $userCanCloseClassroom = true;
        } elseif ($user->hasRole(['manager', 'admin'])) {
            $canClassroomEdit = true;
            $userCanCloseClassroom = true;
        }
        $canClassroomClose = $userCanCloseClassroom
            && $classroom->isOpening
            && $classroom->spentTime >= $classroom->hours;

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
                ($classroom->isOpening ?
                    homeUrl('opening-classrooms') : homeUrl('closed-classrooms'))
                : ($classroom->isOpening ?
                    adminUrl('opening-classrooms') : adminUrl('closed-classrooms')),
            'classroom' => $classroom,
            'class_times' => $lastMonthClassTimes->sortBy('start_at'), // need sorted again
            'class_time_order_start' => $countAllClassTimes - $countLastMonthClassTimes + 1,
            'has_previous_month_class_times' => $hasPreviousMonthClassTimes,
            'previous_year' => $previousYear,
            'previous_month' => $previousMonth,
            'can_classroom_edit' => $canClassroomEdit,
            'can_classroom_close' => $canClassroomClose,
            'teacher' => $classroom->teacherProfile,
            'student' => $classroom->studentProfile,
            'supporter' => $classroom->supporter,
            'date_js_format' => DateTimeHelper::compoundJsFormat('shortDate', ' ', 'shortTime'),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('close')) {
            return $this->close($request, $id);
        }

        abort(404);
        return '';
    }

    protected function close(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        $userCanCloseClassroom = false;
        if ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                abort(404);
            }
            $userCanCloseClassroom = true;
        } elseif ($user->hasRole(['manager', 'admin'])) {
            $userCanCloseClassroom = true;
        }
        if (!$userCanCloseClassroom
            || !$classroom->isOpening
            || $classroom->spentTime < $classroom->hours
        ) {
            abort(404);
        }

        $this->_rdrUrl($request, homeUrl('classrooms'), $rdrUrl, $errorRdrUrl);

        try {
            $this->classroomRepository->close();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}