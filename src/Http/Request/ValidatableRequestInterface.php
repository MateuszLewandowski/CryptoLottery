<?php 

namespace App\Http\Request;

use App\Core\Result\Result;

interface ValidatableRequestInterface
{
    public function validate(): Result;
}