<?php

namespace Knapsack\Compass\Contracts;

use Knapsack\Compass\Support\Request;

interface Responsable
{
    public function toResponse(Request $request);
}
