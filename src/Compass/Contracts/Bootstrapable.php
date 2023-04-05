<?php

namespace Knapsack\Compass\Contracts;

use Knapsack\Compass\App;

interface Bootstrapable
{
    public function bootstrap(App $app);
}
