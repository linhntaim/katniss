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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect']
    ],
    function () {
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

        Route::group(
            [
                'middleware' => 'auth'
            ],
            function () {
                // activation
                Route::get(homeRoute('auth/inactive'), 'Auth\AuthController@getInactive');
                Route::post(homeRoute('auth/inactive'), 'Auth\AuthController@postInactive');
            });
    });
