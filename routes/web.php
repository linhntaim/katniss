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
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize']
], function () {
    Route::any(homeRoute('extra'), 'ViewController@extra');

    Route::get(homeRoute('errors/{code}'), 'ViewController@error');
    Route::get(adminRoute('errors/{code}'), 'ViewController@error');

    Route::group([
        'namespace' => 'Home',
    ], function () {
        Route::get('/', 'ExampleController@index');
        Route::get(homeRoute('example/social-sharing'), 'ExampleController@getSocialSharing');
        Route::get(homeRoute('example/facebook-comments'), 'ExampleController@getFacebookComments');
        Route::get(homeRoute('example/widgets'), 'ExampleController@getWidgets');
        Route::get(homeRoute('example/my-settings'), 'ExampleController@getMySettings');
        Route::get(homeRoute('example/pages'), 'ExampleController@getPages');
        Route::get(homeRoute('example/pages/{id}'), 'ExampleController@getPage')->where('id', '[0-9]+');
        Route::get(homeRoute('example/articles'), 'ExampleController@getArticles');
        Route::get(homeRoute('example/articles/{id}'), 'ExampleController@getArticle')->where('id', '[0-9]+');
        Route::get(homeRoute('example/article-categories/{id}'), 'ExampleController@getCategoryArticles')->where('id', '[0-9]+');
        Route::get(homeRoute('example/public-conversation'), 'ExampleController@getPublicConversation');
    });


    Route::get(homeRoute('me/settings'), 'Admin\SettingsController@index');
    Route::put(homeRoute('me/settings'), 'Admin\SettingsController@update');

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

        Route::get(homeRoute('auth/activate/{id}/{activation_code}'), 'ActivateController@getActivation')->where('id', '[0-9]+');
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
        Route::any(adminRoute('extra'), 'ViewController@extra');
        // my account
        Route::get(homeRoute('me/account'), 'Admin\AccountController@index');
        Route::put(homeRoute('me/account'), 'Admin\AccountController@update');
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
                Route::get(adminRoute('app-options/{id}/edit'), 'AppOptionController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('app-options/{id}'), 'AppOptionController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('app-options/{id}'), 'AppOptionController@destroy')->where('id', '[0-9]+');
                //Extensions
                Route::get(adminRoute('extensions'), 'ExtensionController@index');
                Route::get(adminRoute('extensions/{name}/edit'), 'ExtensionController@edit');
                Route::put(adminRoute('extensions/{name}'), 'ExtensionController@update');
                //Widgets
                Route::get(adminRoute('widgets'), 'WidgetController@index');
                Route::post(adminRoute('widgets'), 'WidgetController@store');
                Route::get(adminRoute('widgets/{id}/edit'), 'WidgetController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('widgets/{id}'), 'WidgetController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('widgets/{id}'), 'WidgetController@destroy')->where('id', '[0-9]+');
                //Langs
                Route::get(adminRoute('ui-lang/php'), 'UiLangController@editPhp');
                Route::put(adminRoute('ui-lang/php'), 'UiLangController@updatePhp');
                Route::get(adminRoute('ui-lang/email'), 'UiLangController@editEmail');
                Route::put(adminRoute('ui-lang/email'), 'UiLangController@updateEmail');
                //Roles
                Route::get(adminRoute('user-roles'), 'RoleController@index');
                //Users
                Route::get(adminRoute('users'), 'UserController@index');
                Route::get(adminRoute('users/create'), 'UserController@create');
                Route::post(adminRoute('users'), 'UserController@store');
                Route::get(adminRoute('users/{id}/edit'), 'UserController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('users/{id}'), 'UserController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('users/{id}'), 'UserController@destroy')->where('id', '[0-9]+');
                //Link Categories
                Route::get(adminRoute('link-categories'), 'LinkCategoryController@index');
                Route::get(adminRoute('link-categories/create'), 'LinkCategoryController@create');
                Route::post(adminRoute('link-categories'), 'LinkCategoryController@store');
                Route::get(adminRoute('link-categories/{id}/edit'), 'LinkCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('link-categories/{id}'), 'LinkCategoryController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('link-categories/{id}'), 'LinkCategoryController@destroy')->where('id', '[0-9]+');
                Route::get(adminRoute('link-categories/{id}/sort'), 'LinkCategoryController@sort')->where('id', '[0-9]+');
                //Links
                Route::get(adminRoute('links'), 'LinkController@index');
                Route::get(adminRoute('links/create'), 'LinkController@create');
                Route::post(adminRoute('links'), 'LinkController@store');
                Route::get(adminRoute('links/{id}/edit'), 'LinkController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('links/{id}'), 'LinkController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('links/{id}'), 'LinkController@destroy')->where('id', '[0-9]+');
                //Pages
                Route::get(adminRoute('pages'), 'PageController@index');
                Route::get(adminRoute('pages/create'), 'PageController@create');
                Route::post(adminRoute('pages'), 'PageController@store');
                Route::get(adminRoute('pages/{id}/edit'), 'PageController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('pages/{id}'), 'PageController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('pages/{id}'), 'PageController@destroy')->where('id', '[0-9]+');
                //Article Categories
                Route::get(adminRoute('article-categories'), 'ArticleCategoryController@index');
                Route::get(adminRoute('article-categories/create'), 'ArticleCategoryController@create');
                Route::post(adminRoute('article-categories'), 'ArticleCategoryController@store');
                Route::get(adminRoute('article-categories/{id}/edit'), 'ArticleCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('article-categories/{id}'), 'ArticleCategoryController@update');
                Route::delete(adminRoute('article-categories/{id}'), 'ArticleCategoryController@destroy')->where('id', '[0-9]+');
                //Articles
                Route::get(adminRoute('articles'), 'ArticleController@index');
                Route::get(adminRoute('articles/create'), 'ArticleController@create');
                Route::post(adminRoute('articles'), 'ArticleController@store');
                Route::get(adminRoute('articles/{id}/edit'), 'ArticleController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('articles/{id}'), 'ArticleController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('articles/{id}'), 'ArticleController@destroy')->where('id', '[0-9]+');
                //Media Categories
                Route::get(adminRoute('media-categories'), 'MediaCategoryController@index');
                Route::get(adminRoute('media-categories/create'), 'MediaCategoryController@create');
                Route::post(adminRoute('media-categories'), 'MediaCategoryController@store');
                Route::get(adminRoute('media-categories/{id}/edit'), 'MediaCategoryController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('media-categories/{id}'), 'MediaCategoryController@update');
                Route::delete(adminRoute('media-categories/{id}'), 'MediaCategoryController@destroy')->where('id', '[0-9]+');
                Route::get(adminRoute('media-categories/{id}/sort'), 'MediaCategoryController@sort')->where('id', '[0-9]+');
                //Media
                Route::get(adminRoute('media-items'), 'MediaController@index');
                Route::get(adminRoute('media-items/create'), 'MediaController@create');
                Route::post(adminRoute('media-items'), 'MediaController@store');
                Route::get(adminRoute('media-items/{id}/edit'), 'MediaController@edit')->where('id', '[0-9]+');
                Route::put(adminRoute('media-items/{id}'), 'MediaController@update')->where('id', '[0-9]+');
                Route::delete(adminRoute('media-items/{id}'), 'MediaController@destroy')->where('id', '[0-9]+');
            });
        });
        #endregion
    });
});