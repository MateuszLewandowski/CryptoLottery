<?php 

namespace App\Factory\DTO\Lottery;

use App\Factory\DTO\AbstractDTOFactory;
use App\Factory\DTO\FactorableDTOInterface;
use App\Factory\Entity\FactorableEntityInterface;
use App\Model\DTO\Lottery\TransactionDTO;

final class TransactionDTOFactory extends AbstractDTOFactory
{
    public function __construct(
    ) {
        $this->required = [
            'id',
            'hash',
            'transaction_from',
            'transaction_to',
            'value',
            'gas',
            'gas_price',
            'created_at',
            'timestamp',
            'block_number',
            'block_hash',
        ];
    }

    public function create(FactorableEntityInterface $transaction): FactorableDTOInterface
    {
        return $this->make(
            dto: TransactionDTO::class, 
            properties: $this->extractEntityProperties(
                object: $transaction
            )
        );
    }
}