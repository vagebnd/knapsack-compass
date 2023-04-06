<?php

namespace Knapsack\Compass\Support;

use Illuminate\Config\Repository as ConfigRepository;
use Knapsack\Compass\Contracts\Config\Repository as ContractsConfigRepository;

class Repository extends ConfigRepository implements ContractsConfigRepository
{
}
