<?php 

namespace App\Http\Request\Authorization;

use App\Core\Result\Result;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class SignInRequest extends Request implements ValidatableRequestInterface
{
    public function validate(): Result 
    {
        return new Result;
    }
}