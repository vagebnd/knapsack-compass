<?php

namespace Knapsack\Compass\Contracts;

interface ViewContract
{
    public function render(string $view, array $data = []);

    public function exists(string $view);
}
