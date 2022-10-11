<?php 

namespace App\Service\Wallet;

use App\Core\Result\Result;

interface GetWalletCryptocurrencyEthServiceInterface
{
    public function action(string $address): array|Result;
}