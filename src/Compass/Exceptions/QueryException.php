<?php

namespace Knapsack\Compass\Exceptions;

use Exception;

class QueryException extends Exception
{
    public $code = 500;
    public $message = '';

    public function __construct($message = '')
    {
        $this->message = $message;
    }
}
