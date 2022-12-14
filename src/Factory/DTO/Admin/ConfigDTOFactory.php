<?php 

namespace App\Factory\DTO\Admin;

use App\Factory\DTO\AbstractDTOFactory;
use App\Factory\DTO\FactorableDTOInterface;
use App\Factory\Entity\FactorableEntityInterface;
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

    public function create(FactorableEntityInterface $config): FactorableDTOInterface
    {
        return $this->make(
            dto: ConfigDTO::class, 
            properties: $this->extractEntityProperties(
                object: $config
            )
        );
    }

}