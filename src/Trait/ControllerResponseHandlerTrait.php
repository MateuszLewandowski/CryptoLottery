<?php 

namespace App\Trait;

use Symfony\Component\HttpFoundation\Response;

trait ControllerResponseHandlerTrait 
{
    private int $code = Response::HTTP_BAD_REQUEST;
    private mixed $payload;
    private ?string $error = null;
}