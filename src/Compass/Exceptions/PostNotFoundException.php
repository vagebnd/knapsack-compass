<?php

namespace Knapsack\Compass\Exceptions;

use RuntimeException;

class PostNotFoundException extends RuntimeException
{
    public $code = 404;
    public $message = '';

    public function __construct($postType)
    {
        $this->message = "No query results for model [{$postType}].";
    }
}
