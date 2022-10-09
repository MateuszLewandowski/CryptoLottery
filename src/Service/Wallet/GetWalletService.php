<?php 

namespace App\Service\Wallet;

use App\Entity\Wallet;
use App\Factory\Entity\User\WalletFactory;
use App\Repository\WalletRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class GetWalletService implements GetWalletServiceInterface
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletFactory $walletFactory,
    ) {
    }
    
    public function action(string $address): Wallet|false 
    {
        $wallet = $this->walletRepository->get(
            address: $address
        );
        if (! $wallet instanceof Wallet) {
            $wallet = $this->walletFactory->create(
                arguments: [
                    'address' => $address
                ]
            );
            if (! $wallet instanceof Wallet) {
                return false;
            }
            $this->walletRepository->save(
                entity: $wallet,
                flush: true,
            );
        }
        return $wallet;
    }
}