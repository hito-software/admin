<?php

namespace Hito\Admin;

use Hito\Admin\Providers\AppServiceProvider;
use Hito\Admin\Providers\RouteServiceProvider;
use Hito\Core\BaseServiceProvider;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        app()->register(AppServiceProvider::class);
        app()->register(RouteServiceProvider::class);
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hito-admin');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'hito-admin');

        $this->registerAssetDirectory('public', 'admin');
    }
}
