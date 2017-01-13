<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\ClassTime;
use Katniss\Everdeen\Utils\AppConfig;

class ClassTimeRepository extends ModelRepository
{
    public function getById($id)
    {
        return ClassTime::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return ClassTime::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return ClassTime::all();
    }

    public function create($classroomId, $subject, $hours, $startAt, $content = null)
    {
        DB::beginTransaction();
        try {
            $classTime = ClassTime::create([
                'classroom_id' => $classroomId,
                'subject' => $subject,
                'content' => $content,
                'hours' => $hours,
                'start_at' => $startAt,
            ]);

            $countClassTimes = $classTime->classroom->countClassTimes;
            if ($countClassTimes % _k('periodic_class_time') == 0) {
                $classTimePeriodic = ClassTime::create([
                    'classroom_id' => $classroomId,
                    'subject' => $countClassTimes,
                    'content' => '',
                    'hours' => 0,
                    'start_at' => $startAt,
                    'type' => ClassTime::TYPE_PERIODIC,
                ]);
            }

            logInfo('Class time created.', $classTime->toArray());

            DB::commit();
            return empty($classTimePeriodic) ? $classTime : [$classTime, $classTimePeriodic];
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createReview($userId, $rate, $review = null)
    {
        $classTime = $this->model();

        try {
            $review = $classTime->reviews()->create([
                'user_id' => $userId,
                'rate' => $rate,
                'rates' => serialize([]),
                'review' => $review,
            ]);

            logInfo('Class time reviewed.', [
                'class_time_id' => $classTime->id,
                'review' => $review->toArray(),
            ]);

            return $review;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createRichReview($userId, array $rates, $review = null)
    {
        $classTime = $this->model();

        try {
            $review = $classTime->reviews()->create([
                'user_id' => $userId,
                'rates' => serialize($rates),
                'review' => $review,
            ]);

            logInfo('Class time rich reviewed.', [
                'class_time_id' => $classTime->id,
                'review' => $review->toArray(),
            ]);

            return $review;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($subject, $content = null)
    {
        $classTime = $this->model();

        try {
            logInfo('Class time before updated.', $classTime->toArray());

            $classTime->update([
                'subject' => $subject,
                'content' => $content,
            ]);

            logInfo('Class time updated.', $classTime->toArray());

            return $classTime;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $classTime = $this->model();

        try {
            $classTime->delete();

            logInfo('Class time updated.', $classTime->toArray());

            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}