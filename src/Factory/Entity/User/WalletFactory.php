<?php 

namespace App\Factory\Entity\User;

use App\Factory\Entity\AbstractEntityFactory;
use App\Entity\Wallet;

final class WalletFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
        $this->required = [
            'address',
        ];
    }

    public function create(array $arguments = []): Wallet
    {
        return $this->make(
            entity: new Wallet, arguments: $arguments
        );
    }
}