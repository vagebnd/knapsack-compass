<?php

namespace Knapsack\Compass\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static bool has($key)
 * @method static mixed get($key, $default = null)
 * @method static void prepend($key, $value)
 * @method static void push($key, $value)
 * @method static void set($key, $value = null)
 *
 * @see \Illuminate\Config\Repository
 */
class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'config';
    }
}
