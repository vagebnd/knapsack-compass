<?php
namespace Knapsack\Compass\Support\View;

use Exception;
use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\ViewContract;

class DirectivesLoader
{
    public function load(App $app)
    {
        $view = $app->make(ViewContract::class);

        $view->directive('loop', function () {
            return '<?php if (have_posts()) { while (have_posts()) { the_post(); ?>';
        });

        $view->directive('endloop', function () {
            return '<?php }} ?>';
        });
    }
}
