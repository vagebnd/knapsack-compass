<?php

namespace Compass\Core\Boostrap;

use Compass\App;
use Compass\Contracts\Bootstrapable;
use Compass\Routing\Registrar;
use Compass\Routing\Router;

class LoadRoutes implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        $app->instance(Registrar::class, Registrar::make($app->make(Router::class)->loadRoutes()));
    }
}
