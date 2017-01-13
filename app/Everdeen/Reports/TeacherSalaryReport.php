<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-13
 * Time: 16:10
 */

namespace Katniss\Everdeen\Reports;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\SalaryJump;
use Katniss\Everdeen\Repositories\SalaryJumpRepository;

class TeacherSalaryReport extends Report
{
    protected $year;
    protected $month;

    /**
     * @var SalaryJump
     */
    protected $lastSalaryJump;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;

        parent::__construct();
    }

    public function getLastSalaryJump()
    {
        return $this->lastSalaryJump;
    }

    public function getHeader()
    {
        return [
            '#',
            trans('label.display_name'),
            trans('label.email'),
            'Skype ID',
            trans('label.phone'),
            trans('label.teaching_hours'),
            trans('label.salary_jump') . ' (' . settings()->currency . ' / 1 ' . trans_choice('label.hour_lc', 1) . ')',
            trans('label.total'),
        ];
    }

    public function getDataAsFlatArray()
    {
        $flat = [];
        $order = 0;
        foreach ($this->data as $item) {
            $flat[] = [
                ++$order,
                $item['teacher']['display_name'],
                $item['teacher']['email'],
                $item['teacher']['skype_id'],
                $item['teacher']['phone'],
                $item['hours'],
                $item['salary_jump'],
                $item['total'],
            ];
        }
        return $flat;
    }

    public function prepare()
    {
        try {
            $salaryJumpRepository = new SalaryJumpRepository();
            $this->lastSalaryJump = $salaryJumpRepository->getLast($this->year, $this->month);
            if (empty($this->lastSalaryJump)) {
                return;
            }

            $classTimes = DB::table('class_times')
                ->select([
                    DB::raw('SUM(' . DB::getTablePrefix() . 'class_times.hours) as hours'),
                    'classrooms.teacher_id',
                ])
                ->join('classrooms', 'classrooms.id', '=', 'class_times.classroom_id')
                ->whereYear('class_times.start_at', $this->year)
                ->whereMonth('class_times.start_at', $this->month)
                ->groupBy('classrooms.teacher_id')
                ->get();

            if ($classTimes->count() <= 0) {
                return;
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

            foreach ($classTimes as $classTime) {
                $teacher = $teachers->where('id', $classTime->teacher_id)->first();
                $this->data[] = [
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
                    'salary_jump' => $this->lastSalaryJump->formattedJumpCurrencyNoSign,
                    'total' => toFormattedCurrency($classTime->hours * $this->lastSalaryJump->jump, $this->lastSalaryJump->currency, true),
                ];
            }
        } catch (\Exception $ex) {
            throw new KatnissException($ex->getMessage());
        }
    }
}