<?php

namespace Hito\Admin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            Route::middleware(['web', 'auth'])
                ->name('admin.')
                ->prefix('admin')
                ->group(__DIR__ . '/../../routes/web.php');
        });
    }
}
