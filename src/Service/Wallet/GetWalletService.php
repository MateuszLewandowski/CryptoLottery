<?php 

namespace App\Service\Wallet;

use App\Entity\Wallet;
use App\Factory\Entity\User\WalletFactory;
use App\Repository\WalletRepository;

final class GetWalletService implements GetWalletServiceInterface
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletFactory $walletFactory,
    ) {
    }
    
    public function action(string $wallet): Wallet|false 
    {
        $wallet = $this->walletRepository->get(
            wallet: $wallet
        );
        if (! $wallet instanceof Wallet) {
            $wallet = $this->walletFactory->create(
                arguments: [
                    'wallet' => $wallet
                ]
            );
        }
        return $wallet;
    }
}