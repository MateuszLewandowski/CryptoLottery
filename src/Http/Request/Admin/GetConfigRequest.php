<?php 

namespace App\Http\Request\Admin;

use App\Core\Result\Result;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetConfigRequest extends Request implements ValidatableRequestInterface
{
    public function validate(): Result 
    {
        return new Result(
            code: Response::HTTP_OK
        );
    }
}