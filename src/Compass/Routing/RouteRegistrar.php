<?php

namespace Knapsack\Compass\Routing;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * @method RouteRegistrar prefix(string $prefix)
 */
class RouteRegistrar
{
    protected $allowedAttributes = [
        'as',
        'namespace',
        'prefix',
        'admin',
    ];

    private Router $router;

    private $attributes = [];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function attribute($key, $value = null)
    {
        if (! in_array($key, $this->allowedAttributes)) {
            throw new InvalidArgumentException("Attribute [{$key}] does not exist.");
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function group($callback)
    {
        $this->router->group($this->attributes, $callback);

        return $this;
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, $this->allowedAttributes)) {
            return $this->attribute($method, array_key_exists(0, $parameters) ? $parameters[0] : true);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
