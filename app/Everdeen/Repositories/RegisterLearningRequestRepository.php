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
use Katniss\Everdeen\Models\RegisterLearningRequest;
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;

class RegisterLearningRequestRepository extends ModelRepository
{
    public function getById($id)
    {
        return RegisterLearningRequest::where('id', $id)->firstOrFail();
    }

    public function getPaged()
    {
        return RegisterLearningRequest::orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getNewlyPaged()
    {
        return RegisterLearningRequest::newly()->orderBy('updated_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getProcessedPaged()
    {
        return RegisterLearningRequest::processed()->orderBy('updated_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return RegisterLearningRequest::all();
    }

    public function createAdult($ageRange,
                                $learningTargets, $learningTargetOther,
                                $learningForms, $learningFormOther,
                                $studentId, array $professionalSkills, $skypeId = null, $teacherId = null)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($studentId);
            if (!empty($skypeId)) {
                $user->update([
                    'skype_id' => $skypeId,
                ]);
            }
            if ($user->professionalSkills()->count() > 0) {
                $user->professionalSkills()->sync($professionalSkills);
            } else {
                $user->professionalSkills()->attach($professionalSkills);
            }

            $storedLearningTargets = [];
            foreach ($learningTargets as $learningTarget) {
                $storedLearningTargets[$learningTarget] = $learningTarget == 100 ?
                    $learningTargetOther : $learningTarget;
            }

            $storedLearningForms = [];
            foreach ($learningForms as $learningForm) {
                $storedLearningForms[$learningForm] = $learningForm == 100 ?
                    $learningFormOther : $learningForm;
            }

            $data = [
                'for_children' => false,
                'age_range' => $ageRange,
                'learning_targets' => serialize($storedLearningTargets),
                'learning_forms' => serialize($storedLearningForms),
                'student_id' => $studentId,
            ];
            if (!empty($teacherId)) {
                $data['teacher_id'] = $teacherId;
            }
            $learningRequest = RegisterLearningRequest::create($data);

            DB::commit();

            return $learningRequest;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function createChildren($ageRange,
                                $learningTargets, $learningTargetOther,
                                $learningForms, $learningFormOther,
                                $studentId, $childrenFullName, $skypeId = null, $teacherId = null)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($studentId);
            if (!empty($skypeId)) {
                $user->update([
                    'skype_id' => $skypeId,
                ]);
            }

            $storedLearningTargets = [];
            foreach ($learningTargets as $learningTarget) {
                $storedLearningTargets[$learningTarget] = $learningTarget == 101 ?
                    $learningTargetOther : $learningTarget;
            }

            $storedLearningForms = [];
            foreach ($learningForms as $learningForm) {
                $storedLearningForms[$learningForm] = $learningForm == 101 ?
                    $learningFormOther : $learningForm;
            }

            $data = [
                'for_children' => true,
                'age_range' => $ageRange,
                'children_full_name' => $childrenFullName,
                'learning_targets' => serialize($storedLearningTargets),
                'learning_forms' => serialize($storedLearningForms),
                'student_id' => $studentId,
            ];
            if (!empty($teacherId)) {
                $data['teacher_id'] = $teacherId;
            }
            $learningRequest = RegisterLearningRequest::create($data);

            DB::commit();

            return $learningRequest;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $learningRequest = $this->model();

        try {
            $learningRequest->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}