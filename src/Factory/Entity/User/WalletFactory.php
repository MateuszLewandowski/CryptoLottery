<?php 

namespace App\Factory\Entity\User;

use App\Factory\Entity\AbstractEntityFactory;
use App\Entity\Lottery\Draw;
use App\Entity\Wallet;
use App\Repository\WalletRepository;

final class WalletFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
        $this->required = [
            'wallet',
        ];
    }

    public function create(array $arguments = []): Draw
    {
        return $this->make(
            entity: new Wallet, arguments: $arguments
        );
    }
}