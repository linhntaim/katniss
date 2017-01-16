<?php

/*
|--------------------------------------------------------------------------
| Web API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web_api" middleware group. Now create something great!
|
*/
Route::any('extra', 'WebApiController@extra');

Route::group([
    'namespace' => 'WebApi',
], function () {
    Route::get('instagram/access-token', 'InstagramController@getAccessToken');
    Route::get('instagram/authorize', 'InstagramController@getAuthorize');

    Route::get('user/csrf-token', 'UserController@getCsrfToken');
    Route::get('user/quick-login', 'UserController@getQuickLogin');

    Route::get('conversations/{id}', 'ConversationController@show');
    Route::get('messages', 'MessageController@index');
    Route::post('messages', 'MessageController@store');

    Route::get('teachers', 'TeacherController@index');
    Route::get('students', 'StudentController@index');
    Route::get('supporters', 'UserController@indexSupporter');
    Route::get('authors', 'UserController@indexAuthor');

    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::put('admin/media-categories/{id}', 'MediaCategoryController@update');

        Route::put('me/account/password', 'AccountController@updatePassword');
        Route::put('me/account/skype-id', 'AccountController@updateSkypeId');

        Route::group([
            'middleware' => 'entrust:teacher|manager|admin'
        ], function () {
            Route::put('classrooms/{id}', 'ClassroomController@update');
            Route::post('class-times', 'ClassTimeController@store');
            Route::put('class-times/{id}', 'ClassTimeController@update');
        });

        Route::group([
            'middleware' => 'entrust:teacher|student|manager|admin'
        ], function () {
            Route::post('class-times/{id}/reviews', 'ClassTimeController@storeReviews');
            Route::post('class-times/{id}/rich-reviews', 'ClassTimeController@storeRichReviews');
        });

        Route::group([
            'middleware' => 'entrust:teacher|student|supporter|manager|admin'
        ], function () {
            Route::get(homeRoute('classrooms/{id}'), 'ClassroomController@show');
        });

        Route::group([
            'middleware' => 'entrust:manager|admin'
        ], function () {
            Route::get('admin/learning-requests/{id}', 'LearningRequestController@show');
            Route::get('admin/salary-report', 'SalaryReportController@index');
        });
    });
});