<?php

namespace App\Http\Middleware;

use App\Core\Result\Result;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractMiddleware implements MiddlewareInterface 
{
    private $middleware;

    public function set(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->middleware = $middleware;
        return $middleware;
    }

    public function validate(mixed $value): Result 
    {
        return $this->middleware instanceof AbstractMiddleware
            ? $this->middleware->validate($value)
            : new Result(
                code: Response::HTTP_OK
            );
    }
}