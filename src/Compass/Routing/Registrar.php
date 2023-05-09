<?php

namespace Knapsack\Compass\Routing;

use Knapsack\Compass\Exceptions\HttpResponseException;
use Knapsack\Compass\Routing\Registrar\RequestHandler;
use Knapsack\Compass\Support\Collections\Arr;
use RuntimeException;

class Registrar
{
    protected Router $router;

    private function __construct(Router $router)
    {
        $this->router = $router;

        add_filter('theme_page_templates', $this->registerPageTemplates(), 10, 1);
        add_filter('template_include', $this->resolve(), 10, 3);
        add_action('admin_menu', $this->registerAdminPages());
    }

    public static function make(Router $router)
    {
        return new self($router);
    }

    protected function registerAdminPages()
    {
        return function () {
            foreach ($this->router->listAdminRoutes() as $adminPageRoute) {
                $adminPageRoute->getRootPage()->add();

                $adminPageRoute->listSubPages()->each(function ($subPage) {
                    $subPage->add();
                });
            }
        };
    }

    protected function registerPageTemplates()
    {
        return function ($templates) {
            foreach ($this->router->listTemplateRoutes() as $route) {
                $templates[$route->serialize()] = $route->getTemplate();
            }

            // Call adminRun callback on current route.
            if ($route = $this->router->getCurrentRoute()) {
                $route->adminRun();
            }

            return $templates;
        };
    }

    protected function resolve()
    {
        return function ($templatePath) {
            if (is_404() && ! str_ends_with($templatePath, '404.php')) {
                throw new HttpResponseException('Not found', 404);
            }

            if ($route = $this->getTemplateForPage()) {
                return $route->run();
            }

            return $templatePath;
        };
    }

    private function getPageIdentifier()
    {
        return get_post_meta(get_the_ID(), '_wp_page_template', true);
    }

    private function getTemplateForPage()
    {
        return $this->router->findByHash($this->getPageIdentifier());
    }
}
