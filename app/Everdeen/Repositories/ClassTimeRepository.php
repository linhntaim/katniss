<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

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
        try {
            $classTime = ClassTime::create([
                'classroom_id' => $classroomId,
                'subject' => $subject,
                'content' => $content,
                'hours' => $hours,
                'start_at' => $startAt,
            ]);

            return $classTime;
        } catch (\Exception $ex) {
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

            return $review;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($subject, $content = null)
    {
        $classTime = $this->model();

        try {
            $classTime->update([
                'subject' => $subject,
                'content' => $content,
            ]);

            return $classTime;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $classroom = $this->model();

        try {
            $classroom->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}