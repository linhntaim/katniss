<?php

namespace Katniss\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Katniss\Everdeen\Events\PasswordChanged;
use Katniss\Everdeen\Events\UserCreated;
use Katniss\Everdeen\Listeners\PasswordChangedEmailing;
use Katniss\Everdeen\Listeners\UserActivationEmailing;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            UserActivationEmailing::class,
        ],
        PasswordChanged::class => [
            PasswordChangedEmailing::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
