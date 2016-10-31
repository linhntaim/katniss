<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'web-api',
    'namespace' => 'WebApi',
], function () {
    Route::get('user/csrf-token', 'UserController@getCsrfToken');
    Route::get('user/quick-login', 'UserController@getQuickLogin');
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize']
], function () {
    Route::get('/', 'Home\HomepageController@index');
    Route::get(homeRoute('me/settings'), 'Admin\SettingsController@index');
    Route::post(homeRoute('me/settings'), 'Admin\SettingsController@update');

    Route::group([
        'namespace' => 'Auth',
    ], function () {
        Route::get(homeRoute('auth/login'), 'LoginController@showLoginForm')->name('login');
        Route::post(homeRoute('auth/login'), 'LoginController@login');
        Route::get(homeRoute('auth/logout'), 'LoginController@logout');

        Route::get(homeRoute('auth/social/{provider}'), 'LoginController@redirectToSocialAuthProvider');
        Route::get(homeRoute('auth/social/callback/{provider}'), 'LoginController@handleSocialAuthProviderCallback');

        Route::get(homeRoute('auth/register'), 'RegisterController@showRegistrationForm');
        Route::post(homeRoute('auth/register'), 'RegisterController@register');

        Route::get(homeRoute('auth/register/social'), 'RegisterController@showSocialRegistrationForm');
        Route::post(homeRoute('auth/register/social'), 'RegisterController@socialRegister');

        Route::get(homeRoute('auth/activate/{id}/{activation_code}'), 'ActivateController@getActivation')
            ->where('id', '[0-9]+');
        Route::get(homeRoute('auth/inactive'), 'ActivateController@getInactive');
        Route::post(homeRoute('auth/inactive'), 'ActivateController@postInactive');

        Route::get(homeRoute('password/email'), 'ForgotPasswordController@getEmail');
        Route::post(homeRoute('password/email'), 'ForgotPasswordController@postEmail');
        Route::get(homeRoute('password/reset/{token}'), 'ResetPasswordController@getReset');
        Route::post(homeRoute('password/reset'), 'ResetPasswordController@postReset');
    });

    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::get(homeRoute('me/account'), 'Admin\AccountController@index');
        Route::post(homeRoute('me/account'), 'Admin\AccountController@update');
        // document
        Route::any(homeRoute('me/documents/connector'), 'DocumentController@getConnector');
        Route::any(homeRoute('me/documents/for/ckeditor'), 'DocumentController@forCkeditor');
        Route::any(homeRoute('me/documents/for/popup/{input_id}'), 'DocumentController@forPopup');

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