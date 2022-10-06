<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsPositive extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_positive = is_integer($value) && $value > 0.0;
        return new Result(
            code: $is_positive ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_positive ?: "Given value of @key has to be an integer and be positive." 
        );
    }
}