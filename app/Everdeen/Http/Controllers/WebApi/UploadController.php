<?php

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\Storage\StorePhoto;

class UploadController extends WebApiController
{
    public function useBlueImp(Request $request)
    {
        try {
            $defaultSize = 1000; // pixels
            $storePhoto = new StorePhoto($request->file('image_file')->getRealPath());
            $storePhoto->resize($defaultSize, $defaultSize);
            $storePhoto->save();
            $storePhoto->moveToCollection();
            return response()->json([
                'files' => [
                    [
                        'url' => $storePhoto->getUrl(),
                    ],
                ],
            ]);
        } catch (\Exception $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }

    public function destroyBlueImp(Request $request)
    {
        try {
            if (!$request->has('file')) {
                $this->responseFail();
            }
            $filePath = public_path(str_replace(url('/'), '', $request->input('file')));
            @unlink($filePath);
            return $this->responseSuccess();
        } catch (\Exception $ex) {
            return $this->responseFail($ex->getMessage());
        }
    }
}
