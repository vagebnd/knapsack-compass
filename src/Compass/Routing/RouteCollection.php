<?php

namespace Knapsack\Compass\Routing;

use Knapsack\Compass\Exceptions\RouteException;
use Knapsack\Compass\Routing\Routes\TemplateRoute;
use Knapsack\Compass\Routing\Routes\AdminPageRoute;

class RouteCollection
{
    protected $routes = [];

    public function add(Route $route)
    {
        // TODO: We should check the action also.
        if (isset($this->routes[$route->endpoint])) {
            throw RouteException::routeAlreadyExists($route->endpoint);
        }

        $this->routes[$route->endpoint] = $route;
    }

    public function find(string $endpoint)
    {
        return $this->routes[$endpoint] ?? null;
    }

    public function all()
    {
        return $this->routes;
    }

    public function listTemplateRoutes()
    {
        return array_filter($this->routes, function ($route) {
            return $route instanceof TemplateRoute;
        });
    }

    public function listAdminRoutes()
    {
        return array_filter($this->routes, function ($route) {
            return $route instanceof AdminPageRoute;
        });
    }
}
