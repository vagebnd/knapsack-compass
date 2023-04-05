<?php

namespace Knapsack\Compass\Core\Boostrap;

use ErrorException;
use Exception;
use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Contracts\Debug\ExceptionHandler;
use Throwable;

class HandleExceptions implements Bootstrapable
{
    /**
     * Reserved memory so that errors can be displayed properly on memory exhaustion.
     *
     * @var string|null
     */
    public static $reservedMemory;

    /**
     * The application instance.
     *
     * @var App
     */
    protected static $app;

    public function bootstrap(App $app)
    {
        self::$reservedMemory = str_repeat('x', 32768);

        static::$app = $app;

        error_reporting(-1);

        set_error_handler($this->forwardsTo('handleError'));

        set_exception_handler($this->forwardsTo('HandleException'));

        register_shutdown_function($this->forwardsTo('handleShutdown'));
    }

    public function handleError($level, $message, $file = '', $line = 0)
    {
        if (error_reporting() && $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    public function HandleException(Throwable $e)
    {
        self::$reservedMemory = null;

        try {
            $this->getExceptionHandler()->report($e);
        } catch (Exception $e) {
        }

        $this->getExceptionHandler()->render($e);
    }

    public function handleShutdown()
    {
        self::$reservedMemory = null;

        if (! is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->HandleException(new Exception($error['message'], 0));
        }
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    protected function getExceptionHandler()
    {
        return static::$app->make(ExceptionHandler::class);
    }

    public function forwardsTo($method)
    {
        return function (...$arguments) use ($method) {
            return $this->{$method}(...$arguments);
        };
    }
}
