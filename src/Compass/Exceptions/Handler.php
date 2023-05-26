<?php

namespace Knapsack\Compass\Exceptions;

use Knapsack\Compass\Contracts\Debug\ExceptionHandler;
use Knapsack\Compass\Support\Facades\Config;
use Knapsack\Compass\Support\Facades\Request;
use Throwable;
use Whoops\Handler\PrettyPageHandler;

class Handler implements ExceptionHandler
{
    public function report(Throwable $e)
    {
        // Log exception here.
    }

    public function render(Throwable $e)
    {
        if (Request::expectsJson()) {
            $response = new \WP_REST_Response([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());

            wp_send_json($response, $e->getCode());
        }

        if (wp_get_environment_type() === 'local') {
            if (class_exists('\Whoops\Run')) {
                $pageHandler = (new PrettyPageHandler)
                    ->setEditor(Config::get('app.editor', 'vscode'));

                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler($pageHandler);

                echo $whoops->handleException($e);
            }

            throw $e;
        } else {
            status_header($e->getCode(), $e->getMessage());

            return vgb_view('exceptions.generic', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
