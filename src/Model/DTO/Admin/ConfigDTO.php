<?php 

namespace App\Model\DTO\Admin;

use App\Factory\DTO\FactorableDTOInterface;

final class ConfigDTO implements FactorableDTOInterface
{
    public function __construct(
        private readonly string $draw_begins_at_hour,
        private readonly int $draw_begins_at_day_no,
        private readonly int $draw_begins_at_concrete_day,
        private readonly bool $draw_is_concrete_day_set,
        private readonly int $lottery_ticket_cost,
        private readonly int $lottery_required_tickets_sum,
        private readonly int $fee_basic,
    ) {
    }
}