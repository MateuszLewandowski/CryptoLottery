<?php 

namespace App\Http\Request\Lottery\Ticket;

use App\Core\Result\Result;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class GetLotteryTicketsCollectionRequest extends Request implements ValidatableRequestInterface
{
    public function validate(): Result 
    {
        return new Result;
    }
}