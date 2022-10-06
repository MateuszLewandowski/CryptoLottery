<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsFloat extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_float = is_float($value);
        return new Result(
            code: $is_float ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_float ?: "Given value of @key has to be an float." 
        );
    }
}