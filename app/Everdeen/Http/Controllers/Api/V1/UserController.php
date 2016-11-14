<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Utils\Storage\StorePhotoByCropperJs;
use Katniss\Everdeen\Models\User;

class UserController extends ApiController
{
    public function postAvatarUsingCropperJs(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$this->validate($request, [
                'cropper_image_file' => 'required',
            ])
            ) {
                return $this->responseFail($this->getValidationErrors());
            }

            $storePhoto = new StorePhotoByCropperJs(
                $request->file('cropper_image_file')->getRealPath(),
                $request->input('cropper_image_data')
            );
            $storePhoto->move(userPublicPath($user->profilePictureDirectory), randomizeFilename());
            $user->url_avatar = publicUrl($storePhoto->getTargetFileRealPath());

            $storePhoto = $storePhoto->duplicate(userPublicPath($user->profilePictureDirectory), randomizeFilename('thumb'));
            $storePhoto->resize(User::AVATAR_THUMB_WIDTH, User::AVATAR_THUMB_HEIGHT);
            $storePhoto->save();
            $user->url_avatar_thumb = publicUrl($storePhoto->getTargetFileRealPath());

            $user->save();

            return $this->responseSuccess([
                'store_path' => $user->url_avatar
            ]);
        } catch (\Exception $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
