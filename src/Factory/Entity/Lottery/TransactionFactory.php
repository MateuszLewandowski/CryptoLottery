<?php 

namespace App\Factory\Entity\Lottery;

use App\Factory\Entity\AbstractEntityFactory;
use App\Entity\Transaction;

final class TransactionFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
        $this->required = [
            'draw', 'hash', 'transaction_from', 'transaction_to', 'value', 'gas', 'gas_price'
        ];
    }

    public function create(array $arguments = []): Transaction
    {
        return $this->make(
            entity: new Transaction, arguments: $arguments
        );
    }
}