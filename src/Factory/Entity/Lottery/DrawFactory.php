<?php 

namespace App\Factory\Entity\Lottery;

use App\Factory\Entity\AbstractEntityFactory;
use App\Entity\Lottery\Draw;

final class DrawFactory extends AbstractEntityFactory
{
    public function __construct(
    ) {
    }

    public function create(array $arguments = []): Draw
    {
        return $this->make(
            entity: new Draw, arguments: $arguments
        );
    }
}