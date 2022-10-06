<?php 

namespace App\Factory\DTO\Admin;

use App\Entity\Admin\Config;
use App\Factory\DTO\AbstractDTOFactory;
use App\Model\DTO\Admin\ConfigDTO;

final class ConfigDTOFactory extends AbstractDTOFactory
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

    public function create(Config $config): ConfigDTO
    {
        return $this->make(
            DTO: ConfigDTO::class, object: $config
        );
    }
}