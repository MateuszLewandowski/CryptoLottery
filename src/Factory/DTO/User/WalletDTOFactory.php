<?php 

namespace App\Factory\DTO\User;

use App\Factory\DTO\AbstractDTOFactory;
use App\Factory\DTO\FactorableDTOInterface;
use App\Factory\DTO\Lottery\TicketDTOFactory;
use App\Factory\Entity\FactorableEntityInterface;
use App\Model\DTO\User\WalletDTO;

final class WalletDTOFactory extends AbstractDTOFactory
{
    public function __construct(
        private TicketDTOFactory $ticketDTOFactory,
    ) {
        $this->required = [
            'id', 
            'address', 
            'tickets', 
        ];
    }

    public function create(FactorableEntityInterface $wallet): FactorableDTOInterface
    {
        $properties = $this->extractEntityProperties(
            object: $wallet
        );

        $tickets = [];
        if (count($properties['tickets']) > 0) {
            foreach ($properties['tickets'] as $ticket) {
                $tickets[] = $this->ticketDTOFactory->create($ticket);
            }
        } 
        $properties['tickets'] = $tickets;
        return $this->make(
            dto: WalletDTO::class, 
            properties: $properties
        );
    }

}