<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsPositiveOrZero extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_positive_or_zero = is_integer($value) && $value >= 0.0;
        return new Result(
            code: $is_positive_or_zero ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_positive_or_zero ?: "Given value of @key has to be an integer and be positive or zero." 
        );
    }
}