<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;
use App\Repository\Lottery\DrawRepository;

final class RemoveUnnecessaryDraw implements RemoveUnnecessaryDrawInterface
{
    public function __construct(
        private DrawRepository $drawRepository,
    ) {
    }
    
    public function action(Draw $draw): void
    {
        $this->drawRepository->remove($draw);
        return;
    }
}