<?php

namespace Knapsack\Compass\Core\Bootstrap;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Contracts\FilesystemContract;
use Knapsack\Compass\Support\Filesystem;

class ConfigureFilesystem implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        if (! function_exists('WP_Filesystem')) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
        }

        if (! WP_Filesystem()) {
            request_filesystem_credentials(site_url());
            return;
        }

        global $wp_filesystem;

        $app->instance(FilesystemContract::class, new Filesystem($wp_filesystem));
    }
}
