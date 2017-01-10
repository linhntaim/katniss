<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Classroom;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class ClassroomRepository extends ModelRepository
{
    public function getById($id)
    {
        return Classroom::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return Classroom::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchOpeningPaged($name = null, $teacher = null, $student = null, $supporter = null)
    {
        $classrooms = Classroom::opening()->orderBy('created_at', 'desc');
        if (!empty($name)) {
            $classrooms->where('name', 'like', '%'.$name.'%');
        }
        if (!empty($teacher)) {
            $classrooms->where('teacher_id', $teacher);
        }
        if (!empty($student)) {
            $classrooms->where('student_id', $student);
        }
        if (!empty($supporter)) {
            $classrooms->where('supporter_id', $supporter);
        }
        return $classrooms->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchClosedPaged($name = null, $teacher = null, $student = null, $supporter = null)
    {
        $classrooms = Classroom::closed()->orderBy('created_at', 'desc');
        if (!empty($name)) {
            $classrooms->where('name', 'like', '%'.$name.'%');
        }
        if (!empty($teacher)) {
            $classrooms->where('teacher_id', $teacher);
        }
        if (!empty($student)) {
            $classrooms->where('student_id', $student);
        }
        if (!empty($supporter)) {
            $classrooms->where('supporter_id', $supporter);
        }
        return $classrooms->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Classroom::all();
    }

    public function create($teacherId, $studentId, $supporterId, $name, $duration)
    {
        try {
            $classroom = Classroom::create([
                'teacher_id' => $teacherId,
                'student_id' => $studentId,
                'supporter_id' => $supporterId,
                'name' => $name,
                'hours' => NumberFormatHelper::getInstance()->fromFormat($duration),
                'status' => Classroom::STATUS_OPENING,
            ]);

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($teacherId, $studentId, $supporterId, $name, $duration)
    {
        $classroom = $this->model();

        try {
            $data = [
                'name' => $name,
                'hours' => NumberFormatHelper::getInstance()->fromFormat($duration),
            ];
            if (!empty($teacherId)) {
                $data['teacher_id'] = $teacherId;
            }
            if (!empty($studentId)) {
                $data['student_id'] = $studentId;
            }
            if (!empty($supporterId)) {
                $supporter = User::find($supporterId);
                if (!$supporter->hasRole('supporter')) {
                    throw new \Exception(trans('error.is_not_role_supporter'));
                }
                $data['supporter_id'] = $supporterId;
            }
            $classroom->update($data);

            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function open()
    {
        $classroom = $this->model();

        try {
            $classroom->update([
                'status' => Classroom::STATUS_OPENING,
            ]);
            return $classroom;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function close()
    {
        $classroom = $this->model();

        try {
            $classroom->update([
                'status' => Classroom::STATUS_CLOSED,
            ]);
            return $classroom;
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