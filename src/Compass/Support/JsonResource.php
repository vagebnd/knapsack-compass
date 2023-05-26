<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Collection;
use Knapsack\Compass\Contracts\Responsable;
use Knapsack\Compass\Support\Traits\ForwardsCalls;

class JsonResource implements Responsable
{
    use ForwardsCalls;

    public $resource;

    public static function make(...$parameters)
    {
        // @phpstan-ignore-next-line
        return new static(...$parameters);
    }

    public static function collection($resources)
    {
        if ($resources instanceof Collection) {
            $resources = $resources->values()->toArray();
        }

        return new JsonResourceCollection($resources, static::class);
    }

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function toArray(Request $request)
    {
        return $this->resource;
    }

    public function toResponse(Request $request)
    {
        $data = $this->toArray($request);

        foreach ($data as $key => $value) {
            if ($value instanceof Responsable) {
                $data[$key] = $value->toResponse($request);
            }
        }

        return $data;
    }

    public function __get($key)
    {
        return $this->resource->{$key};
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->resource, $method, $parameters);
    }
}
