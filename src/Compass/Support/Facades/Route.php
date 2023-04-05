<?php

namespace Compass\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Compass\Routing\Router;

/**
 * @method static \Compass\Routing\Router template(string $template, array|string|callable|null $action = null)
 *
 * @see \Illuminate\Routing\Router
 */
class Route extends Facade
{
    public static function getFacadeAccessor()
    {
        return Router::class;
    }
}
