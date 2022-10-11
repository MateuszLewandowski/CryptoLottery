<?php 

namespace App\Service\Wallet;

use App\Core\Result\Result;
use App\Entity\Wallet;
use App\Factory\Entity\User\WalletFactory;
use App\Repository\WalletRepository;
use App\Web3\Web3Client;
use Symfony\Component\HttpFoundation\Response;

final class GetWalletCryptocurrencyEthService implements GetWalletCryptocurrencyEthServiceInterface
{
    public function __construct(
        private WalletRepository $walletRepository,
        private WalletFactory $walletFactory,
        private Web3Client $web3Client,
    ) {
    }
    
    public function action(string $address): array|Result 
    {
        $wallet = $this->walletRepository->get(
            address: $address
        );
        if (! $wallet instanceof Wallet) {
            return new Result(
                code: Response::HTTP_NOT_FOUND,
                message: "Wallet {$address} not found."
            );
        }
        return [
            'eth' => $this->web3Client->getBalance(
                address: $address
            ),
        ];
    }
}