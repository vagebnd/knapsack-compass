<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Str;
use ReflectionClass;

class Enum
{
    protected static $reflectionCache = [];

    public static function asSelectArray(): array
    {
        $array = static::asArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            $selectArray[$value] = static::getDescription($value);
        }

        return $selectArray;
    }

    public static function asArray(): array
    {
        return static::getConstants();
    }

    public static function getDescription($value): string
    {
        return static::getFriendlyKeyName(static::getKey($value));
    }

    protected static function getFriendlyKeyName(string $key): string
    {
        if (ctype_upper(preg_replace('/[^a-zA-Z]/', '', $key))) {
            $key = strtolower($key);
        }

        return ucfirst(str_replace('_', ' ', Str::snake($key)));
    }

    protected static function getReflection(): ReflectionClass
    {
        $class = static::class;

        return static::$reflectionCache[$class] ??= new ReflectionClass($class);
    }

    public static function getKey($value): string
    {
        return array_search($value, static::getConstants(), true);
    }

    protected static function getConstants(): array
    {
        return self::getReflection()->getConstants();
    }
}
