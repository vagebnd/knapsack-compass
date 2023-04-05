<?php

namespace Compass\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Compass\App as CompassApp;

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
