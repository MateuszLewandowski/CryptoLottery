<?php 

namespace App\Model\DTO\User;

use App\Factory\DTO\FactorableDTOInterface;

final class WalletDTO implements FactorableDTOInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $address,
        public readonly array $tickets,
    ) {
    }
}