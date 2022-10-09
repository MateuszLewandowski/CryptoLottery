<?php 

namespace App\Model\DTO\Lottery;

use App\Factory\DTO\FactorableDTOInterface;

final class DrawDTO implements FactorableDTOInterface
{
    public function __construct(
        public readonly string $id,
        public readonly int $tickets_quantity,
        public readonly array $tickets,
        public readonly string $created_at,
        public readonly ?string $launched_at,
        public readonly bool $is_done,
    ) {
    }
}