<?php

namespace Knapsack\Compass\Core\Http;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\KernelContract;
use Knapsack\Compass\Core\Bootstrap\ConfigureFilesystem;
use Knapsack\Compass\Core\Bootstrap\ConfigureViews;
use Knapsack\Compass\Core\Bootstrap\HandleExceptions;
use Knapsack\Compass\Core\Bootstrap\LoadConfiguration;
use Knapsack\Compass\Core\Bootstrap\LoadRoutes;
use Knapsack\Compass\Core\Bootstrap\RegisterBindings;
use Knapsack\Compass\Core\Bootstrap\RegisterFacades;

class Kernel implements KernelContract
{
    protected App $app;

    protected $bootstrappers = [
        ConfigureFilesystem::class,
        HandleExceptions::class,
        RegisterBindings::class,
        RegisterFacades::class,
        LoadConfiguration::class,
        ConfigureViews::class,
        LoadRoutes::class,
    ];

    public function __construct($app = null)
    {
        $this->app = $app ?? App::getInstance();
        $this->bootstrap();
    }

    public function bootstrap()
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }
}
