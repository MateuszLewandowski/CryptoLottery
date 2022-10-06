<?php

namespace App\Http\Middleware;

use App\Core\Result\Result;

interface MiddlewareInterface 
{
    public function set(MiddlewareInterface $middleware): MiddlewareInterface;
    public function validate(mixed $value): Result;
}