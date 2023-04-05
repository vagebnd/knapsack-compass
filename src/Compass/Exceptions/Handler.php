<?php

namespace Knapsack\Compass\Exceptions;

use Knapsack\Compass\Contracts\Debug\ExceptionHandler;
use Throwable;

class Handler implements ExceptionHandler
{
    public function report(Throwable $e)
    {
        // Log exception here.
    }

    public function render(Throwable $e)
    {
        if (wp_get_environment_type() === 'local') {
            $whoops = new \Whoops\Run;
            $whoops->allowQuit(false);
            $whoops->writeToOutput(false);
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

            echo $whoops->handleException($e);
        } else {
            status_header($e->getCode(), $e->getMessage());

            return vgb_view('exceptions.generic', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
