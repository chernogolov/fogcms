<?php

namespace Chernogolov\Fogcms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


class FogcmsServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/fogcms.php', 'fogcms');

        if(is_dir(__DIR__ . '/Migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        }

        if(is_dir(__DIR__ . '/Views')) {
            $this->loadViewsFrom(__DIR__ . '/Views', 'fogcms');
        }

        Gate::define('view-admin', function ($user) {
            return in_array($user->id, [1]);
        });

        Gate::define('view-regs', function ($user) {
            return RegsUsers::where([['user_id', '=', $user->id],['view', '=', 1]])->first();
        });

        include __DIR__.'/Routes/Routes.php';
    }
    public function register()
    {
    }
}