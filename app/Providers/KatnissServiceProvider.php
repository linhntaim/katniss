<?php

namespace Katniss\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

;
use Katniss\Models\Helpers\Session\DatabaseSessionHandler;
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

        if (!session()->has('settings')) {
            session([
                'settings.locale' => 'en',
                'settings.country' => 'US',
                'settings.timezone' => 'UTC',
                'settings.first_day_of_week' => 0,
                'settings.long_date_format' => 0,
                'settings.short_date_format' => 0,
                'settings.long_time_format' => 0,
                'settings.short_time_format' => 0,
            ]);
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
                $homeTheme = config('katniss.home_theme');
                $homeTheme = '\Katniss\Models\Themes\HomeThemes' . '\\' . $homeTheme . '\Theme';
                return new $homeTheme;
            }
        );

        $this->app['admin_theme'] = $this->app->share(
            function () {
                $adminTheme = config('katniss.admin_theme');
                $adminTheme = '\Katniss\Models\Themes\AdminThemes' . '\\' . $adminTheme . '\Theme';
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
    }
}
