<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group([
    'prefix' => 'api/v1',
    'namespace' => 'Api\V1',
    'middleware' => ['api']
], function () {
    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::post('upload/cropper-js', 'UploadController@useJsCropper');

        Route::post('user/{id}/avatar/cropper-js', 'UserController@postAvatarUsingCropperJs');

        Route::group([
            'middleware' => 'entrust:,access-admin'
        ], function () {
            #region Admin Role
            Route::group([
                'middleware' => 'entrust:admin'
            ], function () {
                Route::post('widgets/update-order', 'WidgetController@updateOrder');
                Route::post('link-categories/{id}/update-order', 'LinkCategoryController@updateOrder');
            });
            #endregion
        });
    });
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize']
    ], function () {
        Route::get('/', 'Home\HomepageController@index');
        Route::get(homeRoute('me/settings'), 'Admin\SettingsController@index');
        Route::post(homeRoute('me/settings'), 'Admin\SettingsController@update');
        Route::get(homeRoute('me/account'), 'Admin\AccountController@index');
        Route::post(homeRoute('me/account'), 'Admin\AccountController@update');

        Route::group([
            'namespace' => 'Auth',
        ], function () {
            Route::get(homeRoute('auth/login'), 'AuthController@getLogin');
            Route::post(homeRoute('auth/login'), 'AuthController@postLogin');
            Route::get(homeRoute('auth/logout'), 'AuthController@getLogout');
            Route::get(homeRoute('auth/register'), 'AuthController@getRegister');
            Route::post(homeRoute('auth/register'), 'AuthController@postRegister');
            Route::get(homeRoute('auth/register/social'), 'AuthController@getSocialRegister');
            Route::post(homeRoute('auth/register/social'), 'AuthController@postSocialRegister');
            Route::get(homeRoute('auth/activate/{id}/{activation_code}'), 'AuthController@getActivation')
                ->where('id', '[0-9]+');
            Route::get(homeRoute('auth/social/{provider}'), 'AuthController@redirectToProvider');
            Route::get(homeRoute('auth/social/callback/{provider}'), 'AuthController@handleProviderCallback');
            Route::get(homeRoute('password/email'), 'PasswordController@getEmail');
            Route::post(homeRoute('password/email'), 'PasswordController@postEmail');
            Route::get(homeRoute('password/reset/{token}'), 'PasswordController@getReset');
            Route::post(homeRoute('password/reset'), 'PasswordController@postReset');
            Route::get(homeRoute('auth/inactive'), 'AuthController@getInactive');
            Route::post(homeRoute('auth/inactive'), 'AuthController@postInactive');
        });

        Route::group([
            'middleware' => 'auth'
        ], function () {
            // document
            Route::any(homeRoute('documents/connector'), 'DocumentController@getConnector');
            Route::any(homeRoute('documents/for/ckeditor'), 'DocumentController@forCkeditor');
            Route::any(homeRoute('documents/for/popup/{input_id}'), 'DocumentController@forPopup');

            #region Admin Role
            Route::group([
                'namespace' => 'Admin',
                'middleware' => 'entrust:,access-admin'
            ], function () {
                Route::get(homeRoute('admin'), 'DashboardController@index');
                Route::get(adminRoute('my-documents'), 'DocumentController@index');

                Route::group([
                    'middleware' => 'entrust:admin'
                ], function () {
                    //App Options
                    Route::get(adminRoute('app-options'), 'AppOptionController@index');
                    Route::get(adminRoute('app-options/homepage'), 'AppOptionController@editHomepage');
                    Route::post(adminRoute('app-options/homepage'), 'AppOptionController@updateHomepage');
                    //Extensions
                    Route::get(adminRoute('extensions'), 'ExtensionController@index');
                    Route::get(adminRoute('extensions/{name}/edit'), 'ExtensionController@edit');
                    Route::post(adminRoute('extensions/update'), 'ExtensionController@update');
                    Route::get(adminRoute('extensions/{name}/deactivate'), 'ExtensionController@deactivate');
                    Route::get(adminRoute('extensions/{name}/activate'), 'ExtensionController@activate');
                    //Widgets
                    Route::get(adminRoute('widgets'), 'WidgetController@index');
                    Route::post(adminRoute('widgets'), 'WidgetController@create');
                    Route::get(adminRoute('widgets/{id}/edit'), 'WidgetController@edit')
                        ->where('id', '[0-9]+');
                    Route::post(adminRoute('widgets/update'), 'WidgetController@update');
                    Route::get(adminRoute('widgets/{id}/deactivate'), 'WidgetController@deactivate')
                        ->where('id', '[0-9]+');
                    Route::get(adminRoute('widgets/{id}/activate'), 'WidgetController@activate')
                        ->where('id', '[0-9]+');
                    Route::get(adminRoute('widgets/{id}/delete'), 'WidgetController@destroy')
                        ->where('id', '[0-9]+');
                    Route::post(adminRoute('widgets/clone'), 'WidgetController@copyTo')
                        ->where('id', '[0-9]+');
                    //Langs
                    Route::get(adminRoute('ui-lang/php'), 'UiLangController@editPhp');
                    Route::post(adminRoute('ui-lang/php'), 'UiLangController@updatePhp');
                    Route::get(adminRoute('ui-lang/email'), 'UiLangController@editEmail');
                    Route::post(adminRoute('ui-lang/email'), 'UiLangController@updateEmail');
                    //Roles
                    Route::get(adminRoute('user-roles'), 'RoleController@index');
                    //Users
                    Route::get(adminRoute('users'), 'UserController@index');
                    Route::get(adminRoute('users/add'), 'UserController@create');
                    Route::post(adminRoute('users/add'), 'UserController@store');
                    Route::get(adminRoute('users/{id}/edit'), 'UserController@edit')
                        ->where('id', '[0-9]+');
                    Route::post(adminRoute('users/update'), 'UserController@update');
                    Route::get(adminRoute('users/{id}/delete'), 'UserController@destroy')
                        ->where('id', '[0-9]+');
                    Route::get(adminRoute('users/verifying-certificates'), 'UserController@listVerifyingCertificates');
                    Route::get(adminRoute('users/verifying-certificates/{id}/verify'), 'UserController@verifyCertificate')
                        ->where('id', '[0-9]+');
                    //Link Categories
                    Route::get(adminRoute('link-categories'), 'LinkCategoryController@index');
                    Route::get(adminRoute('link-categories/add'), 'LinkCategoryController@create');
                    Route::post(adminRoute('link-categories/add'), 'LinkCategoryController@store');
                    Route::get(adminRoute('link-categories/{id}/edit'), 'LinkCategoryController@edit')
                        ->where('id', '[0-9]+');
                    Route::post(adminRoute('link-categories/update'), 'LinkCategoryController@update');
                    Route::get(adminRoute('link-categories/{id}/delete'), 'LinkCategoryController@destroy')
                        ->where('id', '[0-9]+');
                    Route::get(adminRoute('link-categories/{id}/sort'), 'LinkCategoryController@layoutSort')
                        ->where('id', '[0-9]+');
                    //Links
                    Route::get(adminRoute('links'), 'LinkController@index');
                    Route::get(adminRoute('links/{id}/delete'), 'LinkController@destroy')
                        ->where('id', '[0-9]+');
                    Route::get(adminRoute('links/add'), 'LinkController@create');
                    Route::post(adminRoute('links/add'), 'LinkController@store');
                    Route::get(adminRoute('links/{id}/edit'), 'LinkController@edit')
                        ->where('id', '[0-9]+');
                    Route::post(adminRoute('links/update'), 'LinkController@update');
                });
            });
            #endregion
        });
    });
});
