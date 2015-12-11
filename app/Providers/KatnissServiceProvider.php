<?php

namespace Katniss\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

;
use Katniss\Models\Helpers\Session\DatabaseSessionHandler;
use Katniss\Models\Helpers\Settings;
use Katniss\Models\Themes\Extensions;
use Katniss\Models\Themes\Widgets;

class KatnissServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        session()->extend('katniss', function ($app) {
            return new DatabaseSessionHandler();
        });

        config([
            'services.facebook.redirect' => url('auth/social/callback/facebook'),
            'services.google.redirect' => url('auth/social/callback/google'),
        ]);

        Validator::extend('password', function ($attribute, $value, $parameters) {
            return isMatchedUserPassword($value);
        });
        Validator::extend('wizard', function ($attribute, $value, $parameters) {
            return isValidWizardKey($value, $parameters[0]);
        });

        if (!defined('ELFINDER_IMG_PARENT_URL')) {
            define('ELFINDER_IMG_PARENT_URL', libraryAsset('elfinder'));
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['home_theme'] = $this->app->share(
            function () {
                $homeThemeName = config('katniss.home_theme');
                $homeTheme = config('katniss.home_themes.' . $homeThemeName);
                return new $homeTheme;
            }
        );

        $this->app['admin_theme'] = $this->app->share(
            function () {
                $adminThemeName = config('katniss.admin_theme');
                $adminTheme = config('katniss.admin_themes.' . $adminThemeName);
                return new $adminTheme;
            }
        );

        $this->app['extensions'] = $this->app->share(
            function () {
                return new Extensions();
            }
        );

        $this->app['widgets'] = $this->app->share(
            function () {
                return new Widgets();
            }
        );

        $this->app['settings'] = $this->app->share(
            function () {
                return new Settings();
            }
        );
    }
}
