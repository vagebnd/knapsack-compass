<?php

namespace Knapsack\Compass\Core\Bootstrap;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Contracts\Debug\ExceptionHandler;
use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Exceptions\Handler;
use Knapsack\Compass\Routing\Router;
use Knapsack\Compass\Support\View;

class RegisterBindings implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        $app->bind(ExceptionHandler::class, Handler::class);

        $app->instance(Router::class, new Router($app));

        $app->singleton(ViewContract::class, function () use ($app) {
            return new View($app);
        });
    }
}
