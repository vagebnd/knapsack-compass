<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Collection;
use Knapsack\Compass\Contracts\Responsable;

class JsonResourceCollection extends Collection implements Responsable
{
    public function __construct($items = [], $className = null)
    {
        if (! is_null($className)) {
            $items = array_map(function ($item) use ($className) {
                return new $className($item);
            }, $items);
        }

        parent::__construct($items);
    }

    public function toResponse(Request $request)
    {
        return $this
            ->map(function ($resource) use ($request) {
                return $resource->toResponse($request);
            })
            ->toArray();
    }
}
