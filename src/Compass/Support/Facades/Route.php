<?php

namespace Knapsack\Compass\Support\Facades;

use Knapsack\Compass\Routing\Router;
use Knapsack\Compass\Routing\RouteRegistrar;
use Knapsack\Compass\Support\Facade;

/**
 * @method static void template(string|callable|null $template, $action = null)
 * @method static mixed adminPage(string $endpoint, callable $callback = null, array $config = [])
 * @method static RouteRegistrar admin()
 * @method static void post(string $path, array $action)
 * @method static void get(string $path, array $action)
 * @method static void delete(string $path, array $action)
 */
class Route extends Facade
{
    public static function getFacadeAccessor()
    {
        return Router::class;
    }
}
