<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 23:44
 */

namespace Katniss\Everdeen\Repositories;

use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Events\UserAfterRegistered;
use Katniss\Everdeen\Events\UserPasswordChanged;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\MailHelper;
use Katniss\Everdeen\Utils\Storage\StorePhotoByCropperJs;

class UserRepository extends ModelRepository
{
    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function getPaged()
    {
        return User::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return User::all();
    }

    public function create($name, $displayName, $email, $password, array $roles = null, $sendWelcomeMail = false, array $globalViewParams = [])
    {
        DB::beginTransaction();
        try {
            $user = User::create(array(
                'display_name' => $displayName,
                'email' => $email,
                'name' => $name,
                'password' => bcrypt($password),
                'url_avatar' => appDefaultUserProfilePicture(),
                'url_avatar_thumb' => appDefaultUserProfilePicture(),
                'activation_code' => str_random(32),
                'active' => false
            ));
            $user->save();
            if ($roles != null) {
                $user->attachRoles($roles);
            }

            if ($sendWelcomeMail) {
                event(new UserAfterRegistered($user, array_merge($globalViewParams, [
                    MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    MailHelper::EMAIL_TO => $email,
                    MailHelper::EMAIL_TO_NAME => $displayName,

                    'password' => $password,
                ])));
            }

            DB::commit();

            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($name, $displayName, $email, $password, array $roles = null, array $globalViewParams = [])
    {
        $user = $this->model();
        DB::beginTransaction();
        try {
            $passwordChanged = false;
            $user->display_name = $displayName;
            $user->email = $email;
            if (!empty($password)) {
                $user->password = bcrypt($password);
                $passwordChanged = true;
            }
            $user->name = $name;
            $user->save();

            if ($roles != null) {
                $hiddenRoles = $user->roles()->where('status', Role::STATUS_HIDDEN)->get();
                if ($hiddenRoles->count() > 0) {
                    $hiddenRoles = $hiddenRoles->pluck('id')->all();
                    $roles = array_merge($roles, $hiddenRoles);
                }
                $user->roles()->sync($roles);
            }

            if ($passwordChanged) {
                event(new UserPasswordChanged($user, $password,
                    array_merge($globalViewParams, [
                        MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                        MailHelper::EMAIL_TO => $email,
                        MailHelper::EMAIL_TO_NAME => $displayName,
                    ])
                ));
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function updateAvatarByCropperJs($imageRealPath, $imageCropData)
    {
        $user = $this->model();

        $urlAvatar = null;
        $urlAvatarThumb = null;

        try {
            $storePhoto = new StorePhotoByCropperJs($imageRealPath, $imageCropData);
            $storePhoto->move(userPublicPath($user->profilePictureDirectory), randomizeFilename());
            $urlAvatar = publicUrl($storePhoto->getTargetFileRealPath());

            $storePhoto = $storePhoto->duplicate(userPublicPath($user->profilePictureDirectory), randomizeFilename('thumb'));
            $storePhoto->resize(User::AVATAR_THUMB_WIDTH, User::AVATAR_THUMB_HEIGHT);
            $storePhoto->save();
            $urlAvatarThumb = publicUrl($storePhoto->getTargetFileRealPath());
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.application') . ' (' . $ex->getMessage() . ')');
        }

        try {
            $user->url_avatar = $urlAvatar;
            $user->url_avatar_thumb = $urlAvatarThumb;
            $user->save();

            return $user;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $user = $this->model();

        if ($user->id == authUser()->id) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.is_current_user')]));
        }
        if ($user->hasRole('owner')) {
            throw new KatnissException(trans('error._cannot_delete', ['reason' => trans('error.is_role_owner')]));
        }

        try {
            $user->delete();
            return true;
        } catch (\Exception $ex) {
            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}