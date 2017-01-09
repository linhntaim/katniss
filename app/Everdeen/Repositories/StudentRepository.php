<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Events\UserCreated;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Student;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserSetting;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\MailHelper;

class StudentRepository extends ModelRepository
{
    public function getById($id)
    {
        return Student::findOrFail($id);
    }

    public function getPaged()
    {
        return Student::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Student::all();
    }

    protected function generateNameFromEmail($email)
    {
        $emailName = strtok($email, '@');
        $name = $emailName;
        $i = 0;
        while (User::where('name', $name)->count() > 0) {
            $name = $emailName . '-' . (++$i);
        }
        return $name;
    }

    public function create($displayName, $email, $password, $phoneCode, $phoneNumber)
    {
        DB::beginTransaction();
        try {
            $settings = UserSetting::create();

            $user = User::create(array(
                'display_name' => $displayName,
                'email' => $email,
                'name' => $this->generateNameFromEmail($email),
                'password' => bcrypt($password),
                'url_avatar' => appDefaultUserProfilePicture(),
                'url_avatar_thumb' => appDefaultUserProfilePicture(),
                'activation_code' => str_random(32),
                'active' => true,
                'setting_id' => $settings->id,
                'phone_code' => $phoneCode,
                'phone_number' => $phoneNumber,
            ));
            $user->save();

            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getByNames(['user', 'student'])->pluck('id')->all();
            $user->attachRoles($roles);

            $student = Student::create([
                'user_id' => $user->id,
            ]);

            event(new UserCreated($user, $password, false,
                array_merge(request()->getTheme()->viewParams(), [
                    MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    MailHelper::EMAIL_TO => $email,
                    MailHelper::EMAIL_TO_NAME => $displayName,
                ])
            ));

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateWhenSigningUp($skypeId)
    {
        $student = $this->model();

        try {
            $student->userProfile->update([
                'skype_id' => $skypeId,
            ]);
            return $student;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }
}