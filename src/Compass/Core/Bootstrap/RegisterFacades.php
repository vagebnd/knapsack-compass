<?php

namespace Knapsack\Compass\Core\Bootstrap;

use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Support\Facade;

class RegisterFacades implements Bootstrapable
{
    public function bootstrap($app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);
    }
}
