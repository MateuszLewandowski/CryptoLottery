<?php 

namespace App\Model\DTO\Admin;

use App\Factory\DTO\FactorableDTOInterface;

final class ConfigDTO implements FactorableDTOInterface
{
    public function __construct(
        public readonly string $draw_begins_at_hour,
        public readonly int $draw_begins_at_day_no,
        public readonly int $draw_begins_at_concrete_day,
        public readonly bool $draw_is_concrete_day_set,
        public readonly float $lottery_ticket_cost,
        public readonly float $lottery_required_tickets_sum,
        public readonly float $fee_basic,
    ) {
    }
}