<?php

namespace Compass\Exceptions;

use Exception;

class ViteException extends Exception
{
    public static function invalidAssetType($file)
    {
        return new self("{ $file } is not a valid asset type.");
    }

    public static function invalidManifest($file)
    {
        return new self("The manifest file { $file } does not exist.");
    }
}
