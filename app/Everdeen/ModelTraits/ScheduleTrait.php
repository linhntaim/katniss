<?php
/**
 * Created by PhpStorm.
 * User: Antoree M
 * Date: 2018-07-10
 * Time: 18:25
 */

namespace Katniss\Everdeen\ModelTraits;


use Carbon\Carbon;
use Katniss\Everdeen\Utils\DateTimeHelper;

trait ScheduleTrait
{
    public function getLocalDataAttribute()
    {
        $sampleTime = DateTimeHelper::getInstance()->sampleTimeFromSchedule($this);
        return [
            'day_of_week_from' => DateTimeHelper::dayOfWeek($sampleTime['from']->dayOfWeek),
            'day_of_week_from_text' => DateTimeHelper::transShortPhpDayOfWeek($sampleTime['from']->dayOfWeek),
            'time_from' => $sampleTime['from']->format(DateTimeHelper::shortTimeFormat()),
            'day_of_week_to' => DateTimeHelper::dayOfWeek($sampleTime['to']->dayOfWeek),
            'day_of_week_to_text' => DateTimeHelper::transShortPhpDayOfWeek($sampleTime['to']->dayOfWeek),
            'time_to' => $sampleTime['to']->format(DateTimeHelper::shortTimeFormat()),
        ];
    }

    public function getDurationAttribute()
    {
        $sampleTime = DateTimeHelper::getInstance()->sampleTimeFromSchedule($this);
        return $sampleTime['duration_in_minutes'] / 60;
    }

    public function getNearestScheduleAttribute()
    {
        $sampleTime = DateTimeHelper::getInstance()->sampleTimeFromSchedule($this, true);
        if ($sampleTime['from']->lt(Carbon::parse(DateTimeHelper::syncNow()))) {
            $sampleTime['from']->addDays(7);
            $sampleTime['to']->addDays(7);
        }
        return $sampleTime;
    }
}