<?php 

namespace App\Model\DTO\Lottery;

use App\Factory\DTO\FactorableDTOInterface;

final class TransactionDTO implements FactorableDTOInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $hash,
        public readonly string $transaction_from,
        public readonly string $transaction_to,
        public readonly int $value,
        public readonly int $gas,
        public readonly int $gas_price,
        public readonly string $created_at,
        public readonly ?string $timestamp = null,
        public readonly ?string $block_number = null,
        public readonly ?string $block_hash = null,
    ) {
    }
}