<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('custom_session', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);

            $guard = new SessionGuard($name, $provider, $app['session.store'], $app['request']);
            $guard->setCookieJar($app['cookie']);
            $guard->setDispatcher($app['events']);
            $guard->setRequest($app->refresh('request', $guard, 'setRequest'));

            $guard->setRememberDuration(60 * 24 * 1);

            return $guard;
        });
    }
}
