<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Repositories\UserRepository;

class UserController extends ApiController
{
    protected $userRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->userRepository = new UserRepository();
    }

    public function postAvatarUsingCropperJs(Request $request, $id)
    {
        $this->userRepository->model($id);

        if (!$this->validate($request, [
            'cropper_image_file' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $user = $this->userRepository->updateAvatarByCropperJs(
                $request->file('cropper_image_file')->getRealPath(),
                $request->input('cropper_image_data')
            );

            return $this->responseSuccess([
                'store_path' => $user->url_avatar
            ]);
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
