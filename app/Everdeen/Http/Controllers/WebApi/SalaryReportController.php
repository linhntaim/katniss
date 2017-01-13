<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\SalaryJumpRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;

class SalaryReportController extends WebApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme')->only('index');
    }

    public function index(Request $request)
    {
        if (!$this->customValidate($request, [
            'month_year' => 'required|date_format:' . DateTimeHelper::shortMonthFormat(),
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $date = DateTimeHelper::getInstance()->fromFormat(
                DateTimeHelper::shortMonthFormat(), $request->input('month_year'));

            $salaryJumpRepository = new SalaryJumpRepository();
            $lastSalaryJump = $salaryJumpRepository->getLast($date->format('Y'), $date->format('n'));
            if (empty($lastSalaryJump)) {
                return $this->responseFail();
            }

            $classTimes = DB::table('class_times')
                ->select([
                    DB::raw('SUM(' . DB::getTablePrefix() . 'class_times.hours) as hours'),
                    'classrooms.teacher_id',
                ])
                ->join('classrooms', 'classrooms.id', '=', 'class_times.classroom_id')
                ->whereYear('class_times.start_at', $date->format('Y'))
                ->whereMonth('class_times.start_at', $date->format('m'))
                ->groupBy('classrooms.teacher_id')
                ->get();

            if ($classTimes->count() <= 0) {
                return $this->responseFail();
            }

            $teachers = DB::table('teachers')
                ->select([
                    'users.id',
                    'users.name',
                    'users.display_name',
                    'users.email',
                    'users.skype_id',
                    'users.phone_number',
                    'users.phone_code',
                ])
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->whereIn('teachers.user_id', $classTimes->pluck('teacher_id')->all())
                ->get();

            $report = [];
            foreach ($classTimes as $classTime) {
                $teacher = $teachers->where('id', $classTime->teacher_id)->first();
                $report[] = [
                    'teacher' => [
                        'id' => $teacher->id,
                        'home_url' => homeUrl('teachers/{id}', ['id' => $teacher->id]),
                        'name' => $teacher->name,
                        'display_name' => $teacher->display_name,
                        'email' => $teacher->email,
                        'skype_id' => empty($teacher->skype_id) ? '' : $teacher->skype_id,
                        'phone' => empty($teacher->phone_code) || empty($teacher->phone_number) ?
                            '' : '(+' . allCountry($teacher->phone_code, 'calling_code') . ') ' . $teacher->phone_number,
                    ],
                    'hours' => toFormattedNumber($classTime->hours),
                    'salary_jump' => $lastSalaryJump->formattedJumpCurrencyNoSign,
                    'total' => toFormattedCurrency($classTime->hours * $lastSalaryJump->jump, $lastSalaryJump->currency, true),
                ];
            }

            return $this->responseSuccess([
                'report' => $report,
                'jump' => $lastSalaryJump->formattedJumpCurrency . ' / 1 ' . trans_choice('label.hour_lc', 1),
            ]);
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
