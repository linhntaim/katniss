<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
], function () {
    Route::any('extra', 'ApiController@extra');

    Route::group([
        'namespace' => 'Api\V1',
        'middleware' => 'auth'
    ], function () {
        Route::post('upload/default-image', 'UploadController@useDefaultImage');
        Route::post('upload/cropper-js', 'UploadController@useJsCropper');

        Route::post('user/{id}/avatar/cropper-js', 'UserController@postAvatarUsingCropperJs');

        Route::group([
            'middleware' => 'entrust:,access-admin'
        ], function () {
            #region Admin Role
            Route::group([
                'middleware' => 'entrust:admin'
            ], function () {
                Route::put('link-categories/{id}', 'LinkCategoryController@update');
            });
            #endregion
        });
    });
});
