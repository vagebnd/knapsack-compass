<?php

namespace Knapsack\Compass;

use Knapsack\Compass\App;

class Plugin extends App
{
    protected bool $shouldBindInstance = false;

    public function __construct(string $name)
    {
        parent::__construct(
            WP_CONTENT_DIR . '/plugins/' . $name,
            WP_CONTENT_URL . '/plugins/' . $name
        );
    }
}
