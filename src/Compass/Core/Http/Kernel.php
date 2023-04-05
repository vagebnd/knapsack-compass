<?php

namespace Compass\Core\Http;

use Compass\App;
use Compass\Contracts\KernelContract;
use Compass\Core\Boostrap\HandleExceptions;
use Compass\Core\Boostrap\LoadConfiguration;
use Compass\Core\Boostrap\LoadRoutes;
use Compass\Core\Boostrap\RegisterBindings;
use Compass\Core\Boostrap\RegisterFacades;

class Kernel implements KernelContract
{
    protected App $app;

    protected $bootstrappers = [
        HandleExceptions::class,
        RegisterBindings::class,
        LoadConfiguration::class,
        RegisterFacades::class,
        LoadRoutes::class,
    ];

    public function __construct()
    {
        $this->app = App::getInstance();
        $this->bootstrap();
    }

    public function bootstrap()
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }
}
