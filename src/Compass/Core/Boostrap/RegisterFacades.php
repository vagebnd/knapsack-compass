<?php

namespace Knapsack\Compass\Core\Boostrap;

use Illuminate\Support\Facades\Facade;
use Knapsack\Compass\Contracts\Bootstrapable;

class RegisterFacades implements Bootstrapable
{
    public function bootstrap($app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);
    }
}
