<?php 

namespace App\Factory\Entity\Admin;

use App\Entity\Admin\Config;
use App\Factory\Entity\AbstractEntityFactory;
use DateTimeImmutable;

final class ConfigFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
        $this->required = [
            'draw_begins_at_hour', 
            'draw_begins_at_day_no', 
            'draw_begins_at_concrete_day', 
            'draw_is_concrete_day_set', 
            'lottery_ticket_cost', 
            'lottery_required_tickets_sum', 
            'fee_basic'
        ];
    }

    public function create(array $arguments = []): Config
    {
        if (isset($arguments['draw_begins_at_hour'])) {
            $arguments['draw_begins_at_hour'] = new DateTimeImmutable(
                datetime: $arguments['draw_begins_at_hour']
            );
        }
        return $this->make(
            entity: new Config, arguments: $arguments
        );
    }
}