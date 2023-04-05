<?php

namespace Knapsack\Compass\Exceptions;

class VersionException extends \RuntimeException
{
    public static function invalidPhpVersion(string $version)
    {
        return new self("You must be using PHP {$version} or greater.");
    }

    public static function invalidWordPressVersion(string $version)
    {
        return new self("You must be using WordPress {$version} or greater.");
    }
}
