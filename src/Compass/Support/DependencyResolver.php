<?php

namespace Compass\Support;

use BadMethodCallException;
use Compass\App;
use Compass\Routing\Traits\DependencyResolverTrait;

abstract class DependencyResolver
{
    use DependencyResolverTrait;

    protected $container;

    public function __construct()
    {
        $this->container = App::getInstance();
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     */
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($this->resolveClassMethodDependencies($parameters, $this, $method)));
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
