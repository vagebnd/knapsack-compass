<?php

namespace Knapsack\Compass\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public $code = 422;
    public $message = '';

    public function __construct($errorBag = [])
    {
        $this->message = $errorBag;
    }
}
