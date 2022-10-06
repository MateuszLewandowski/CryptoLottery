<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsNotNull extends AbstractMiddleware
{
    public function validate(mixed $value): Result 
    {
        $is_not_null = !is_null($value) && $value !== '' && !empty($value);
        return new Result(
            code: $is_not_null ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_not_null ?: "Given value of @key has not to be a null." 
        );
    }
}