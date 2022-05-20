<?php

namespace Hito\Admin;

use Hito\Admin\Providers\AppServiceProvider;
use Hito\Admin\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
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

        $files = app('files');
        $localPublic = __DIR__.'/../public';
        $publicPath = public_path(config('app.asset_path') . '/' . config('app.asset_directory_main'));
        $destinationPath = "{$publicPath}/admin";

        if (!$files->isDirectory($destinationPath) && $files->isDirectory($localPublic)) {
            $files->ensureDirectoryExists($publicPath);
            $files->link($localPublic, $destinationPath);
        }
    }
}
