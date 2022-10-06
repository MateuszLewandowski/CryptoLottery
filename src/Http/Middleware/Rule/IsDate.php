<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

final class IsDate extends AbstractMiddleware
{
    private const FORMAT = 'Y-m-d H:i:s';

    public function validate(mixed $value): Result 
    {
        $is_date = DateTime::createFromFormat(self::FORMAT, $value);
        return new Result(
            code: $is_date ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_date ?: "Given value of @key has to be a date." 
        );
    }
}