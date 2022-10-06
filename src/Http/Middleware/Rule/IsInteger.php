<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsInteger extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_integer = is_integer($value);
        return new Result(
            code: $is_integer ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_integer ?: "Given value of @key has to be an integer." 
        );
    }
}