<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsBoolean extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_boolean = is_bool($value);
        return new Result(
            code: $is_boolean ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_boolean ?: "Given value of @key has to be a boolean." 
        );
    }
}