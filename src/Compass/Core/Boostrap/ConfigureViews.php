<?php
namespace Knapsack\Compass\Core\Boostrap;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Support\View\DirectivesLoader;

class ConfigureViews implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        $app->make(DirectivesLoader::class)->load($app);
    }
}
