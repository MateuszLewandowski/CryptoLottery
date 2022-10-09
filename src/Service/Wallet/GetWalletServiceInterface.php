<?php 

namespace App\Service\Wallet;

use App\Entity\Wallet;

interface GetWalletServiceInterface
{
    public function action(string $address): Wallet|false;
}