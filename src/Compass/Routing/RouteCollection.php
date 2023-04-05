<?php

namespace Knapsack\Compass\Routing;

use Knapsack\Compass\Exceptions\RouteException;

class RouteCollection
{
    protected $routes = [];

    public function add(Route $route)
    {
        if (isset($this->routes[$route->template])) {
            throw RouteException::routeAlreadyExists($route->template);
        }

        $this->routes[$route->template] = $route;
    }

    public function find(string $template)
    {
        return $this->routes[$template] ?? null;
    }

    public function all()
    {
        return $this->routes;
    }
}
