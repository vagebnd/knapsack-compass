<?php

namespace Knapsack\Compass\Support\Facades;

use Knapsack\Compass\Routing\Router;
use Knapsack\Compass\Support\Facade;

class Route extends Facade
{
    public static function getFacadeAccessor()
    {
        return Router::class;
    }
}
