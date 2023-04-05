<?php

namespace Knapsack\Compass\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Knapsack\Compass\App as CompassApp;

/**
 * @method static string getName()
 * @method static string prefix($value)
 * @method static string unprefix($value)
 */
class App extends Facade
{
    public static function getFacadeAccessor()
    {
        return CompassApp::class;
    }
}
