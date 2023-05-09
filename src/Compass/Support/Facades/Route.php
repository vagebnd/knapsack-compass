<?php

namespace Knapsack\Compass\Support\Facades;

use Knapsack\Compass\Routing\Router;
use Knapsack\Compass\Support\Facade;

/**
 * @method static void template(string $template, string|callable|null $action)
 * @method static void adminPage(string $endpoint, string|callable|null $action, ?array $config = [])
 */
class Route extends Facade
{
    public static function getFacadeAccessor()
    {
        return Router::class;
    }
}
