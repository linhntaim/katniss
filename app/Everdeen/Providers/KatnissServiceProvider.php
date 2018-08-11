<?php

namespace Katniss\Everdeen\Providers;

use Illuminate\Support\ServiceProvider;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Connectors\ConnectionFactory;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Session\DatabaseSessionHandler;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Session\FileSessionHandler;
use Katniss\Everdeen\Vendors\Laravel\Socialite\SocialiteManager;
use Katniss\Everdeen\Vendors\Mcamara\LaravelLocalization\LaravelLocalization;
use Katniss\Everdeen\Utils\Settings;

class KatnissServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        session()->extend('katniss_database', function ($app) {
            $connection = $app['config']['session.connection'];
            return new DatabaseSessionHandler($app['db']->connection($connection), $app['config']['session.table'], $app['config']['session.lifetime'], $app);
        });
        session()->extend('katniss_file', function ($app) {
            return new FileSessionHandler($app['files'], $app['config']['session.files'], $app['config']['session.lifetime']);
        });

        config([
            'services.facebook.redirect' => url('auth/social/callback/facebook'),
            'services.google.redirect' => url('auth/social/callback/google'),
        ]);

        validator()->extend('password', function ($attribute, $value, $parameters) {
            return isMatchedUserPassword($value);
        });
        validator()->extend('wizard', function ($attribute, $value, $parameters) {
            return isValidWizardKey($value, $parameters[0]);
        });
        validator()->extend('extensions', function ($attribute, $value, $parameters) {
            if (!($value instanceof UploadedFile)) {
                return false;
            }
            return in_array($value->getClientOriginalExtension(), $parameters);
        });
        validator()->extend('decimal', function ($attribute, $value, $parameters) {
            $fractional = intval($parameters[1]);
            $integer = intval($parameters[0]) - $fractional;
            return preg_match('/^\d{0,' . $integer . '}(\.\d{0,' . $fractional . '}){0,1}$/', $value) === 1;
        });
        validator()->extend('re_captcha', 'Katniss\Everdeen\Validators\GoogleReCaptcha@validate');

        if (!defined('ELFINDER_IMG_PARENT_URL')) {
            define('ELFINDER_IMG_PARENT_URL', libraryAsset('elfinder'));
        }

        if (!defined('KATNISS_EMPTY_STRING')) {
            define('KATNISS_EMPTY_STRING', '');
        }

        if (!defined('KATNISS_DEFAULT_APP')) {
            define('KATNISS_DEFAULT_APP', 1);
        }

        DateTimeHelper::syncNow(true);
        StoreFile::init();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('request', \Katniss\Everdeen\Http\Request::class);

        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        $this->app->singleton(\Illuminate\Notifications\ChannelManager::class, function ($app) {
            return new ChannelManager($app);
        });

        $this->app->singleton('Laravel\Socialite\Contracts\Factory', function ($app) {
            return new SocialiteManager($app);
        });

        $this->app->singleton('laravellocalization', function () {
            return new LaravelLocalization();
        });

        $this->app->singleton('settings', function () {
            return new Settings();
        });
    }
}
