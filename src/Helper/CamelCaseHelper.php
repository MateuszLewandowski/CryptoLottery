<?php 

namespace App\Helper;

final class CamelCaseHelper implements HelperInterface
{
    public static function run(mixed $arg): mixed
    {
        if (is_string($arg)) {
            $arg = explode('_', $arg);
        }
        return implode('', array_map(
            callback: fn($part) => ucfirst($part), 
            array: $arg
        ));
    }
}