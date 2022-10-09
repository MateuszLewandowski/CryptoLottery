<?php 

namespace App\Factory\DTO\Lottery;

use App\Factory\DTO\AbstractDTOFactory;
use App\Factory\DTO\FactorableDTOInterface;
use App\Factory\Entity\FactorableEntityInterface;
use App\Model\DTO\Lottery\TicketDTO;

final class TicketDTOFactory extends AbstractDTOFactory
{
    public function __construct(
    ) {
        $this->required = [
            'id', 
            'created_at', 
            'wallet', 
            'draw', 
        ];
    }

    public function create(FactorableEntityInterface $ticket): FactorableDTOInterface
    {
        return $this->make(
            dto: TicketDTO::class, 
            properties: $this->extractEntityProperties(
                object: $ticket
            )
        );
    }

}