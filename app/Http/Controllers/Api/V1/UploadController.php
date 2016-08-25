<?php

namespace Katniss\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Katniss\Http\Controllers\ApiController;
use Katniss\Models\Helpers\Storage\StorePhotoByCropperJs;

class UploadController extends ApiController
{
    /**
     * Upload image using JsCropper
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function useJsCropper(Request $request)
    {
        try {
            $store = new StorePhotoByCropperJs($request->file('cropper_image_file')->getRealPath(), $request->input('cropper_image_data'));
            return $this->responseSuccess([
                'store_path' => $store->getTargetFileRelativePath()
            ]);
        } catch (\Exception $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
