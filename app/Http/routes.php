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

Route::get('/', 'Home\HomepageController@index');

#region Authentication Routes
Route::get(homePath('auth/login'), 'Auth\AuthController@getLogin');
Route::post(homePath('auth/login'), 'Auth\AuthController@postLogin');
Route::get(homePath('auth/logout'), 'Auth\AuthController@getLogout');
Route::get(homePath('auth/register'), 'Auth\AuthController@getRegister');
Route::post(homePath('auth/register'), 'Auth\AuthController@postRegister');
Route::get(homePath('auth/register/social'), 'Auth\AuthController@getSocialRegister');
Route::post(homePath('auth/register/social'), 'Auth\AuthController@postSocialRegister');
Route::get(homePath('auth/activate/{id}/{activation_code}'), 'Auth\AuthController@getActivation')
    ->where('id', '[0-9]+');
Route::get(homePath('auth/social/{provider}'), 'Auth\AuthController@redirectToProvider');
Route::get(homePath('auth/social/callback/{provider}'), 'Auth\AuthController@handleProviderCallback');
Route::get(homePath('password/email'), 'Auth\PasswordController@getEmail');
Route::post(homePath('password/email'), 'Auth\PasswordController@postEmail');
Route::get(homePath('password/reset/{token}'), 'Auth\PasswordController@getReset');
Route::post(homePath('password/reset'), 'Auth\PasswordController@postReset');
#endregion
