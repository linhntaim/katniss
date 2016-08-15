<?php

namespace Katniss\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Katniss\Http\Controllers\ApiController;
use Katniss\Models\Helpers\JsCropper;

class UploadController extends ApiController
{
    /**
     * Upload image using JsCropper
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function useJsCropper(Request $request) {
        $avatar_basename = uniqid($user->id . '_');

        $success = false;
        $crop = new JsCropper();
        if ($crop->fromUploadFile($request->hasFile('avatar_file') ? $request->file('avatar_file') : null)) {
            $crop->setDataFromJson($request->input('avatar_data', null));
            $crop->setDestination(asset('storage/app/profile_pictures/' . $avatar_basename), storage_path('app/profile_pictures/' . $avatar_basename));
            if ($crop->doCrop()) {
                $user->profile_picture = $crop->getResult();
                $user->save();

                $success = true;
            }
        }

        if (!$success) {
            $response = [
                'success' => false,
                'message' => $crop->getMsg(),
            ];
        } else {
            $response = [
                'success' => true,
                'result' => $crop->getResult()
            ];
        }

        return response()->json($response);
    }
}
