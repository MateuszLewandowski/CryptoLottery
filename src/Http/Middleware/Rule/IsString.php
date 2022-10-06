<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsString extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_string = is_string($value);
        return new Result(
            code: $is_string ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_string ?: "Given value of @key has to be a string." 
        );
    }
}