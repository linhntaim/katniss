<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Repositories\ClassroomRepository;
use Katniss\Everdeen\Repositories\ClassTimeRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomController extends WebApiController
{
    protected $classroomRepository;

    public function __construct()
    {
        parent::__construct();

        $this->classroomRepository = new ClassroomRepository();
    }

    public function show(Request $request, $id)
    {
        if ($request->has('monthly_class_times')) {
            return $this->monthlyClassTimes($request, $id);
        }

        return $this->responseFail();
    }

    public function monthlyClassTimes(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                abort(404);
            }
        } elseif ($user->hasRole('student')) {
            if ($classroom->student_id != $user->id) {
                abort(404);
            }
        } elseif ($user->hasRole('supporter')) {
            if ($classroom->supporter_id != $user->id) {
                abort(404);
            }
        }

        if (!$this->customValidate($request, [
            'year' => 'required',
            'month' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $classTimesOfMonth = $classroom->getClassTimesOfMonth($request->input('year'), $request->input('month'));
        $countAllClassTimes = $classroom->getCountTillClassTimesOfMonth($request->input('year'), $request->input('month'));
        $countLastMonthClassTimes = $classTimesOfMonth->count();
        $hasPreviousMonthClassTimes = false;
        $previousYear = false;
        $previousMonth = false;
        if ($countLastMonthClassTimes > 0) {
            $datetime = new \DateTime($classTimesOfMonth[0]->start_at);
            $datetime->sub(new \DateInterval('P1M'));
            $previousYear = $datetime->format('Y');
            $previousMonth = $datetime->format('m');
            $hasPreviousMonthClassTimes = $classroom->getCountClassTimesOfMonth($previousYear, $previousMonth) > 0;
        }

        return $this->responseSuccess([
            'class_times' => $classTimesOfMonth->map(function (ClassTime $classTime) {
                return [
                    'id' => $classTime->id,
                    'subject' => $classTime->subject,
                    'hours' => $classTime->hours,
                    'duration' => $classTime->duration . ' ' . trans_choice('label.hour_lc', $classTime->hours),
                    'start_at' => $classTime->inverseFullFormattedStartAt,
                    'month_year_start_at' => date('m-Y', strtotime($classTime->start_at)),
                    'trans_month_year_start_at' => transMonthYear($classTime->start_at),
                    'content' => $classTime->content,
                    'html_content' => $classTime->html_content,
                ];
            }),
            'stats' => [
                'sum_hours' => $classTimesOfMonth->sum('hours'),
                'month_year' => date('m-Y', strtotime($classTimesOfMonth[0]->start_at)),
                'trans_month_year' => transMonthYear($classTimesOfMonth[0]->start_at),
            ],
            'class_time_order_end' => $countAllClassTimes,
            'has_previous_month_class_times' => $hasPreviousMonthClassTimes,
            'previous_year' => $previousYear,
            'previous_month' => $previousMonth,
        ]);
    }

    /**
     * Only for Roles: Teacher/Manager/Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->has('only_name')) {
            return $this->updateName($request, $id);
        }

        return $this->responseFail();
    }

    public function updateName(Request $request, $id)
    {
        $classroom = $this->classroomRepository->model($id);
        if (!$classroom->isOpening) {
            abort(404);
        }
        $user = $request->authUser();
        if ($user->hasRole('teacher')) {
            if ($classroom->teacher_id != $user->id) {
                abort(404);
            }
        }

        if (!$this->customValidate($request, [
            'name' => 'required|max:255',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $classroom = $this->classroomRepository->updateName(
                $request->input('name')
            );

            return $this->responseSuccess([
                'classroom' => [
                    'name' => $classroom->name,
                ]
            ]);
        } catch (KatnissException $exception) {
            return $this->responseFail($exception->getMessage());
        }
    }
}