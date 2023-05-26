<?php

namespace Knapsack\Compass\Routing\Registrar;

use Illuminate\Support\Collection;
use Knapsack\Compass\Exceptions\HttpResponseException;
use Knapsack\Compass\Routing\Route;
use Knapsack\Compass\Support\Collections\Arr;

class RequestHandler
{
    public static function handle(Collection $methods)
    {
        $method = strtoupper(Arr::get($_SERVER, 'REQUEST_METHOD', 'GET'));
        $action = $methods->firstWhere('method', $method);

        // TODO:: this messes with uploading images in the media library. And maybe other things.

        // if (! $action) {
        //     throw new HttpResponseException('Method not allowed', 405);
        // }

        // if (! $action instanceof Route) {
        //     throw new HttpResponseException('Invalid route action', 500);
        // }

        // TODO: Test other methods. (put, patch, delete, etc.)

        return function () use ($action) {
            return $action->run();
        };
    }
}
