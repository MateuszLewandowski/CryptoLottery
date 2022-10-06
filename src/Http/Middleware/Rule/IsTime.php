<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

final class IsTime extends AbstractMiddleware
{
    private const FORMAT = 'H:i';

    public function validate(mixed $value): Result 
    {
        $is_time = DateTime::createFromFormat(self::FORMAT, $value);
        return new Result(
            code: $is_time ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_time ?: "Given value of @key has to be a time." 
        );
    }
}