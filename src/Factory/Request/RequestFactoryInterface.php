<?php 

namespace App\Factory\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface 
{
    public static function create(string $class): Request;
}