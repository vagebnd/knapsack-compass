<?php

namespace Knapsack\Compass\Support\Facades;

use Knapsack\Compass\Support\Facade;
use Knapsack\Compass\Contracts\ViewContract;

/**
 * @method static void directive(string $name, callable $handler)
 *
 * @see \Illuminate\Routing\Router
 */
class View extends Facade
{
    public static function getFacadeAccessor()
    {
        return ViewContract::class;
    }
}
