<?php 

namespace App\Factory\Entity\Lottery;

use App\Factory\Entity\AbstractEntityFactory;
use App\Entity\Lottery\Ticket;

final class TicketFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
        $this->required = [
            'wallet', 'draw'
        ];
    }

    public function create(array $arguments = []): Ticket
    {
        return $this->make(
            entity: new Ticket, arguments: $arguments
        );
    }
}