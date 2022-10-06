<?php 

namespace App\Http\Request\User\Lottery;

use App\Core\Result\Result;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class LotteryTicketPurchaseRequest extends Request implements ValidatableRequestInterface
{
    public function validate(): Result 
    {
        return new Result;
    }
}