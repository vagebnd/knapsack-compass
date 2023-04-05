<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;

class Request
{
    use ForwardsCalls;

    private $params;

    public function __construct()
    {
        $this->params = Collection::make($_POST)->merge($_GET);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->params, $method, $parameters);
    }
}
