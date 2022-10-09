<?php 

namespace App\Service\Lottery\Ticket;

use App\Core\Result\Result;

interface BuyLotteryTicketServiceInterface
{
    public function action(string $wallet, int $quantity): Result|array;
}