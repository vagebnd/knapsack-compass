<?php

namespace Knapsack\Compass\Support\Facades;

use Knapsack\Compass\Support\Facade;
use Knapsack\Compass\Support\Request as SupportRequest;

/**
 * @method static bool expectsJson()
 * @method static bool has(string $value)
 * @method static mixed get($value, $default = null)
 * @method static array only(array $value)
 */
class Request extends Facade
{
    public static function getFacadeAccessor()
    {
        return SupportRequest::class;
    }
}
