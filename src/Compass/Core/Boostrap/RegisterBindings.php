<?php

namespace Compass\Core\Boostrap;

use Compass\App;
use Compass\Contracts\Bootstrapable;
use Compass\Contracts\Debug\ExceptionHandler;
use Compass\Contracts\ViewContract;
use Compass\Exceptions\Handler;
use Compass\Routing\Router;
use Compass\Support\View;

class RegisterBindings implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        $app->bind(ExceptionHandler::class, Handler::class);

        $app->instance(Router::class, new Router($app));

        $app->singleton(ViewContract::class, View::class);
    }
}
