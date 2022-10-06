<?php

namespace App\Http\Middleware\Rule;

use App\Core\Result\Result;
use App\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Response;

final class IsDateInTheFuture extends AbstractMiddleware
{
    private const FORMAT = 'Y-m-d H:i:s';

    public function validate(mixed $value): Result 
    {
        $is_date_in_the_future = $value <= date(self::FORMAT);
        return new Result(
            code: $is_date_in_the_future ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            message: $is_date_in_the_future ?: "Given value of @key has to be date in the future." 
        );
    }
}