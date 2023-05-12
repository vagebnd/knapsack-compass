<?php

namespace Knapsack\Compass\Routing;

use Knapsack\Compass\App;
use Knapsack\Compass\Routing\Routes\AdminPageRoute;
use Knapsack\Compass\Routing\Routes\TemplateRoute;
use Skeleton\Support\Plugin;

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
        return $this->addRoute(new TemplateRoute($template, $action));
    }

    /**
     * Register admin pages and menu items.
     */
    public function adminPage(string $endpoint, callable $callback = null, array $config = [])
    {
        $adminPageRoute = new AdminPageRoute($endpoint, $config);

        call_user_func($callback, $adminPageRoute);

        return $this->addRoute($adminPageRoute);
    }

    /**
     * Add a route to the underlying route collection
     */
    public function addRoute(Route $route)
    {
        return $this->routes->add($route);
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

    /**
     * @return TemplateRoute[]
     */
    public function listTemplateRoutes()
    {
        return $this->routes->listTemplateRoutes();
    }

    /**
    * @return AdminPageRoute[]
    */
    public function listAdminRoutes()
    {
        return $this->routes->listAdminRoutes();
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
            $routeFiles = ['templates.php', 'admin.php', 'api.php'];

            foreach ($routeFiles as $routeFile) {
                $path = $this->container->path("routes/$routeFile");

                if (file_exists($path)) {
                    require $path;
                }
            }
        }

        if (file_exists($path)) {
            require $path;
        }

        return $this;
    }

    public function get(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'GET');
    }

    public function post(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'POST');
    }

    public function delete(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'DELETE');
    }

    public function put(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'PUT');
    }

    public function patch(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'PATCH');
    }

    public function options(string $path, array $action)
    {
        $this->registerRoute($path, $action, 'OPTIONS');
    }

    private function registerRoute(string $path, array $action, string $method = 'GET')
    {
        $route = new Route($path, $action, $method);
        $trace = debug_backtrace();
        $filename = pathinfo(basename($trace[2]['file']), PATHINFO_FILENAME);

        switch($filename) {
            case 'api':
                $this->registerApiRoute($route);
                break;
        }
    }

    private function registerApiRoute(Route $route)
    {
        add_action('rest_api_init', function () use ($route) {
            $pluginName = Plugin::getInstance()->make('config')->get('app.name');

            register_rest_route($pluginName, $route->endpoint, [
                'methods' => 'GET',
                'callback' => function () use ($route) {
                    return new \WP_REST_Response($route->run(), 200);
                },
                'permission_callback' => function () {
                    return true;
                },
            ]);
        });
    }
}
