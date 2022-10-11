<?php 

namespace App\Service\Lottery\Ticket;

use App\Entity\Wallet;
use App\Factory\DTO\User\WalletDTOFactory;
use App\Model\DTO\User\WalletDTO;
use App\Repository\WalletRepository;

final class GetTicketsViaWalletService implements GetTicketsViaWalletServiceInterface
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletDTOFactory $walletDTOFactory,
    ) {
    }
    
    public function action(string $address): ?WalletDTO
    {
        $wallet = $this->walletRepository->get(
            address: $address
        );
        return $wallet instanceof Wallet 
            ? $this->walletDTOFactory->create(wallet: $wallet)
            : null;
    }
}