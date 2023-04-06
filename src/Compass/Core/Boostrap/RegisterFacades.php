<?php

namespace Knapsack\Compass\Core\Boostrap;

use Knapsack\Compass\Support\Facade;
use Knapsack\Compass\Contracts\Bootstrapable;

class RegisterFacades implements Bootstrapable
{
    public function bootstrap($app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);
    }
}
