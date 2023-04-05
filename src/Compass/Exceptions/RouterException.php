<?php

namespace Knapsack\Compass\Exceptions;

use Exception;

class RouterException extends Exception
{
    public static function templateNameAlreadyExists(string $template)
    {
        return new self("Template name '{$template}' already exists.");
    }
}
