<?php 

namespace App\Factory\Request;

use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class RequestFactory implements RequestFactoryInterface
{
    /**
     * @todo Logger
     */
    public static function create(string $class): Request
    {
        try {
            Request::setFactory(self::getInstance($class));
            return Request::createFromGlobals();
        } catch (Throwable $e) {
            throw $e;
        } 
    }

    private static function getInstance(string $name): callable 
    {
        return function(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) use ($name) {
            return new $name($query, $request, $attributes, $cookies, $files, $server, $content);
        };
    }
}