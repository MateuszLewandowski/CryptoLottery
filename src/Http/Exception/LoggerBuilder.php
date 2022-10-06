<?php 

namespace App\Http\Exception;

use Throwable;

final class LoggerBuilder
{
    public static function handle(Throwable $e): string
    {
        return $e->getCode() . ': ' . $e->getMessage() . ' at ' . $e->getLine();
    }
}