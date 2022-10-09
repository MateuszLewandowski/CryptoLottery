<?php 

namespace App\Model\DTO\Lottery;

use App\Factory\DTO\FactorableDTOInterface;

final class TicketDTO implements FactorableDTOInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $created_at,
        public readonly array $wallet,
        public readonly array $draw,
    ) {
    }
}