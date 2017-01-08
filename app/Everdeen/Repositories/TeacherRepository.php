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
use Katniss\Everdeen\Models\Teacher;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserSetting;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\MailHelper;

class TeacherRepository extends ModelRepository
{
    public function getById($id)
    {
        return Teacher::findOrFail($id);
    }

    public function getPaged()
    {
        return Teacher::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Teacher::all();
    }

    protected function generateNameFromEmail($email)
    {
        $name = strtok($email, '@');
        $i = 0;
        while (User::where('name', $name)->count() > 0) {
            $name = $name . '-' . (++$i);
        }
        return $name;
    }

    public function create($displayName, $email, $password, $skypeId, $phoneCode, $phoneNumber)
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
                'skype_id' => $skypeId,
                'phone_code' => $phoneCode,
                'phone_number' => $phoneNumber,
            ));
            $user->save();

            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getByNames(['user', 'teacher'])->pluck('id')->all();
            $user->attachRoles($roles);

            $teacher = Teacher::create([
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

    public function updateWhenSigningUp($topics, $aboutMe, $experience, $methodology, $certificates, $otherCertificates, $videoIntroduceUrl)
    {
        $teacher = $this->model();

        try {
            $storedCertificates = [];
            foreach ((array)$certificates as $certificate) {
                $certificate = strtolower($certificate);
                $storedCertificates[$certificate] = $certificate == 'others' ?
                    $otherCertificates : $certificate;
            }
            $teacher->update([
                'about_me' => $aboutMe,
                'experience' => $experience,
                'methodology' => $methodology,
                'video_introduce_url' => $videoIntroduceUrl,
                'certificates' => serialize($storedCertificates),
            ]);
            if ($teacher->topics()->count() > 0) {
                $teacher->topics()->sync($topics);
            } else {
                $teacher->topics()->attach($topics);
            }
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateInformation($topics, $aboutMe, $experience, $methodology, $videoIntroduceUrl, $videoTeachingUrl)
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'about_me' => $aboutMe,
                'experience' => $experience,
                'methodology' => $methodology,
                'video_introduce_url' => $videoIntroduceUrl,
                'video_teaching_url' => $videoTeachingUrl,
            ]);
            if ($teacher->topics()->count() > 0) {
                $teacher->topics()->sync($topics);
            } else {
                $teacher->topics()->attach($topics);
            }
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updatePaymentInfo($country, array $data)
    {
        $teacher = $this->model();

        try {
            $teacher->update([
                'payment_info' => serialize([
                    'country' => $country,
                    'data' => $data,
                ]),
            ]);
            return $teacher;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }
}