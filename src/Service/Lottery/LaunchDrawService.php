<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;
use App\Repository\Lottery\DrawRepository;

final class LaunchDrawService implements LaunchDrawServiceInterface
{
    public function __construct(
        private DrawRepository $drawRepository,
    ) {
    }
    
    public function action(Draw $draw): void
    {
        $this->drawRepository->update($draw);
        return;
    }
}