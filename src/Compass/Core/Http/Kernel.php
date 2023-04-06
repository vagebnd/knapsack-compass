<?php

namespace Knapsack\Compass\Core\Http;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\KernelContract;
use Knapsack\Compass\Core\Boostrap\ConfigureFilesystem;
use Knapsack\Compass\Core\Boostrap\ConfigureViews;
use Knapsack\Compass\Core\Boostrap\HandleExceptions;
use Knapsack\Compass\Core\Boostrap\LoadConfiguration;
use Knapsack\Compass\Core\Boostrap\LoadRoutes;
use Knapsack\Compass\Core\Boostrap\RegisterBindings;
use Knapsack\Compass\Core\Boostrap\RegisterFacades;

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
