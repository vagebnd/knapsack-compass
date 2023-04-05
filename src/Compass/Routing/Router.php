<?php

namespace Knapsack\Compass\Routing;

use Knapsack\Compass\App;

class Router
{
    /**
     * The route collection instance
     *
     * @var RouteCollection
     */
    protected $routes;

    protected $container;

    protected $registrar;

    public function __construct(App $app)
    {
        $this->container = $app;
        $this->routes = new RouteCollection();
    }

    /**
     * Template routing.
     *
     * Determines which template is intended to be loaded.
     *
     * @param  string|callable|null  $action
     */
    public function template(string $template, $action = null)
    {
        return $this->addRoute($template, $action);
    }

    /**
     * Add a route to the underlying route collection
     *
     * @param  string|callable|null  $action
     */
    public function addRoute(string $template, $action = null)
    {
        return $this->routes->add(new Route($template, $action));
    }

    public function findRoute(string $template)
    {
        return $this->routes->find($template);
    }

    public function getCurrentRoute()
    {
        return $this->findByHash(get_page_template_slug());
    }

    public function listRoutes()
    {
        return $this->routes->all();
    }

    public function findByHash(string $hash)
    {
        foreach ($this->listRoutes() as $route) {
            if ($route->serialize() === $hash) {
                return $route;
            }
        }

        return null;
    }

    public function loadRoutes(string $path = null)
    {
        if (is_null($path)) {
            // Make this more flexible.
            $path = get_template_directory().'/routes/templates.php';
        }

        if (file_exists($path)) {
            require $path;
        }

        return $this;
    }
}
