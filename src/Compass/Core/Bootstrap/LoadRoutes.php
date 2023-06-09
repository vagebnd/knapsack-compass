<?php

namespace Knapsack\Compass\Core\Bootstrap;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Routing\Registrar;
use Knapsack\Compass\Routing\Router;

class LoadRoutes implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        $app->instance(Registrar::class, Registrar::make($app->make(Router::class)->loadRoutes()));

        add_action('admin_init', function () {
            rest_get_server();
        });
    }
}
