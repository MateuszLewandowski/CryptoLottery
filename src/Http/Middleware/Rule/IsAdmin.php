<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsAdmin extends AbstractMiddleware
{
    const ADMIN_TOKEN = 'whereismoxbee';

    public function validate(mixed $value): Result 
    {
        $is_valid = $value === SELF::ADMIN_TOKEN;
        return new Result(
            code: $is_valid ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED,
            message: $is_valid ?: 'Unauthorized.' 
        );
    }
}