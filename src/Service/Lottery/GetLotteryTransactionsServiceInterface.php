<?php 

namespace App\Service\Lottery;

use App\Core\Result\Result;

interface GetLotteryTransactionsServiceInterface
{
    public function action(): array|Result;
}