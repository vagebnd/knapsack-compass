<?php

namespace Knapsack\Compass\Support\Traits;

use ReflectionClass;

trait ExposeFilename
{
    public static function getName()
    {
        $className = get_called_class();
        $reflection = new ReflectionClass($className);
        $path = $reflection->getFileName();
        $filename = pathinfo(basename($path), PATHINFO_FILENAME);

        return $filename;
    }
}
