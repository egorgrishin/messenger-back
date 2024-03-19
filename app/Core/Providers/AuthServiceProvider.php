<?php

namespace Core\Providers;

use Core\Classes\Auth\JwtGuard;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
