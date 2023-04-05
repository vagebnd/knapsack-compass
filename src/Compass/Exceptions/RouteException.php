<?php

namespace Compass\Exceptions;

use Exception;

class RouteException extends Exception
{
    public static function routeAlreadyExists(string $route)
    {
        return new self("Route '{$route}' already exists.");
    }
}
