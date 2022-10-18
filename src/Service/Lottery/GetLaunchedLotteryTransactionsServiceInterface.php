<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;

interface GetLaunchedLotteryTransactionsServiceInterface
{
    public function action(Draw $draw): array;
}