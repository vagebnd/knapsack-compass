<?php

namespace Knapsack\Compass\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class could not be instantiated
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
}
