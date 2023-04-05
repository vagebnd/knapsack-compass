<?php

namespace Knapsack\Compass\Contracts\Debug;

use Throwable;

interface ExceptionHandler
{
    public function report(Throwable $e);

    public function render(Throwable $e);
}
