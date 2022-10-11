<?php 

namespace App\Service\Lottery\Ticket;

use App\Model\DTO\User\WalletDTO;

interface GetTicketsViaWalletServiceInterface
{
    public function action(string $address): ?WalletDTO;
}