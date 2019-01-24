<?php

namespace Chernogolov\Fogcms;

use Illuminate\Support\ServiceProvider;


class FogcmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //регистрируем конфиг
//        $this->mergeConfigFrom(__DIR__ . '/../config/fogcms.php', 'fogcms');

        include __DIR__.'/Routes/Routes.php';
    }
    public function register()
    {
    }
}