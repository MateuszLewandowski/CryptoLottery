<?php 

namespace App\Helper;

use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\Response;

final class MathFloatConvertHelper implements HelperInterface
{
    public static function run(mixed $arg): mixed
    {
        if (is_integer($arg)) {
            return (float) bcdiv($arg, 100, 2);
        } 
        else if (is_float($arg)) {
            return bcmul($arg, 100);
        }
        throw new InvalidTypeException(
            code: Response::HTTP_BAD_REQUEST,
            message: "Invalid type. Given: " . gettype($arg) . ", expected: [float|int]"
        );
    }
}