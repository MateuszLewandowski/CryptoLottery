<?php 

namespace App\Service\Lottery\Ticket;

interface BuyLotteryTicketServiceInterface
{
    public function action(string $wallet, int $quantity);
}