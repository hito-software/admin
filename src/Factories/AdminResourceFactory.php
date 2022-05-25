<?php

namespace Hito\Admin\Factories;

use Hito\Admin\Builders\AdminCreateResourceBuilder;
use Hito\Admin\Builders\AdminDeleteResourceBuilder;
use Hito\Admin\Builders\AdminDestroyResourceBuilder;
use Hito\Admin\Builders\AdminEditResourceBuilder;
use Hito\Admin\Builders\AdminIndexResourceBuilder;
use Hito\Admin\Builders\AdminShowResourceBuilder;
use Hito\Admin\Builders\AdminStoreResourceBuilder;
use Hito\Admin\Builders\AdminUpdateResourceBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminResourceFactory
{
    public static function index(LengthAwarePaginator $items, callable $itemCallback): AdminIndexResourceBuilder
    {
        return new AdminIndexResourceBuilder($items, $itemCallback);
    }

    public static function show(): AdminShowResourceBuilder
    {
        return new AdminShowResourceBuilder();
    }

    public static function create(): AdminCreateResourceBuilder
    {
        return new AdminCreateResourceBuilder();
    }

    public static function store(?string $route = null, mixed $routeParameters = []): AdminStoreResourceBuilder
    {
        return new AdminStoreResourceBuilder($route, $routeParameters);
    }

    public static function edit(): AdminEditResourceBuilder
    {
        return new AdminEditResourceBuilder();
    }

    public static function update(?string $route = null, mixed $routeParameters = []): AdminUpdateResourceBuilder
    {
        return new AdminUpdateResourceBuilder($route, $routeParameters);
    }

    public static function delete(): AdminDeleteResourceBuilder
    {
        return new AdminDeleteResourceBuilder();
    }

    public static function destroy(?string $route = null, mixed $routeParameters = []): AdminDestroyResourceBuilder
    {
        return new AdminDestroyResourceBuilder($route, $routeParameters);
    }
}
