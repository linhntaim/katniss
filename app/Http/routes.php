<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
], function () {
    Route::get('/', 'Home\HomepageController@index');

    #region Authentication Routes
    Route::get(homeRoute('auth/login'), 'Auth\AuthController@getLogin');
    Route::post(homeRoute('auth/login'), 'Auth\AuthController@postLogin');
    Route::get(homeRoute('auth/logout'), 'Auth\AuthController@getLogout');
    Route::get(homeRoute('auth/register'), 'Auth\AuthController@getRegister');
    Route::post(homeRoute('auth/register'), 'Auth\AuthController@postRegister');
    Route::get(homeRoute('auth/register/social'), 'Auth\AuthController@getSocialRegister');
    Route::post(homeRoute('auth/register/social'), 'Auth\AuthController@postSocialRegister');
    Route::get(homeRoute('auth/activate/{id}/{activation_code}'), 'Auth\AuthController@getActivation')
        ->where('id', '[0-9]+');
    Route::get(homeRoute('auth/social/{provider}'), 'Auth\AuthController@redirectToProvider');
    Route::get(homeRoute('auth/social/callback/{provider}'), 'Auth\AuthController@handleProviderCallback');
    Route::get(homeRoute('password/email'), 'Auth\PasswordController@getEmail');
    Route::post(homeRoute('password/email'), 'Auth\PasswordController@postEmail');
    Route::get(homeRoute('password/reset/{token}'), 'Auth\PasswordController@getReset');
    Route::post(homeRoute('password/reset'), 'Auth\PasswordController@postReset');
    #endregion

    Route::group([
        'middleware' => 'auth'
    ], function () {
        // activation
        Route::get(homeRoute('auth/inactive'), 'Auth\AuthController@getInactive');
        Route::post(homeRoute('auth/inactive'), 'Auth\AuthController@postInactive');
        // document
        Route::any(homeRoute('documents/connector'), 'DocumentController@getConnector');
        Route::any(homeRoute('documents/for/ckeditor'), 'DocumentController@forCkeditor');
        Route::any(homeRoute('documents/for/popup/{input_id}'), 'DocumentController@forPopup');

        #region Admin Role
        Route::group([
            'middleware' => 'entrust:,access-admin'
        ], function () {
            Route::get(homeRoute('admin'), 'Admin\DashboardController@index');
            Route::get(adminRoute('my-documents'), 'Admin\DocumentController@index');

            Route::group([
                'middleware' => 'entrust:admin'
            ], function () {
                //App Options
                Route::get(adminRoute('app-options'), 'Admin\AppOptionController@index');
                Route::get(adminRoute('app-options/homepage'), 'Admin\AppOptionController@editHomepage');
                Route::post(adminRoute('app-options/homepage'), 'Admin\AppOptionController@updateHomepage');
                //Extensions
                Route::get(adminRoute('extensions'), 'Admin\ExtensionController@index');
                Route::get(adminRoute('extensions/{name}/edit'), 'Admin\ExtensionController@edit');
                Route::post(adminRoute('extensions/update'), 'Admin\ExtensionController@update');
                Route::get(adminRoute('extensions/{name}/deactivate'), 'Admin\ExtensionController@deactivate');
                Route::get(adminRoute('extensions/{name}/activate'), 'Admin\ExtensionController@activate');
                //Widgets
                Route::get(adminRoute('widgets'), 'Admin\WidgetController@index');
                Route::post(adminRoute('widgets'), 'Admin\WidgetController@create');
                Route::get(adminRoute('widgets/{id}/edit'), 'Admin\WidgetController@edit')
                    ->where('id', '[0-9]+');
                Route::post(adminRoute('widgets/update'), 'Admin\WidgetController@update');
                Route::get(adminRoute('widgets/{id}/deactivate'), 'Admin\WidgetController@deactivate')
                    ->where('id', '[0-9]+');
                Route::get(adminRoute('widgets/{id}/activate'), 'Admin\WidgetController@activate')
                    ->where('id', '[0-9]+');
                Route::get(adminRoute('widgets/{id}/delete'), 'Admin\WidgetController@destroy')
                    ->where('id', '[0-9]+');
                Route::post(adminRoute('widgets/clone'), 'Admin\WidgetController@copyTo')
                    ->where('id', '[0-9]+');
                //Langs
                Route::get(adminRoute('ui-lang/php'), 'Admin\UiLangController@editPhp');
                Route::post(adminRoute('ui-lang/php'), 'Admin\UiLangController@updatePhp');
                Route::get(adminRoute('ui-lang/email'), 'Admin\UiLangController@editEmail');
                Route::post(adminRoute('ui-lang/email'), 'Admin\UiLangController@updateEmail');
                //Roles
                Route::get(adminRoute('user-roles'), 'Admin\UserRoleController@index');
                //Users
                Route::get(adminRoute('users'), 'Admin\UserController@index');
                Route::get(adminRoute('users/add'), 'Admin\UserController@create');
                Route::post(adminRoute('users/add'), 'Admin\UserController@store');
                Route::get(adminRoute('users/{id}/edit'), 'Admin\UserController@edit')
                    ->where('id', '[0-9]+');
                Route::post(adminRoute('users/update'), 'Admin\UserController@update');
                Route::get(adminRoute('users/{id}/delete'), 'Admin\UserController@destroy')
                    ->where('id', '[0-9]+');
                Route::get(adminRoute('users/verifying-certificates'), 'Admin\UserController@listVerifyingCertificates');
                Route::get(adminRoute('users/verifying-certificates/{id}/verify'), 'Admin\UserController@verifyCertificate')
                    ->where('id', '[0-9]+');
            });
        });
        #endregion
    });
});
