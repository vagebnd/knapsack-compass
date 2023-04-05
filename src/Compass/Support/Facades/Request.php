<?php

namespace Compass\Support\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\LazyCollection;
use Compass\Support\Request as SupportRequest;

/**
 * @method static array all()
 * @method static array only($keys)
 * @method static Collection range($from, $to)
 * @method static LazyCollection lazy()
 * @method static mixed lazy()
 * @method static mixed median(string|array|null $key)
 * @method static mixed mode()
 * @method static bool has($key)
 * @method static mixed get($key, $default = null)
 */
class Request extends Facade
{
    public static function getFacadeAccessor()
    {
        return SupportRequest::class;
    }
}
