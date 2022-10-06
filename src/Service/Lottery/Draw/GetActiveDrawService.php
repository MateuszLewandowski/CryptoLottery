<?php 

namespace App\Service\Lottery\Draw;

use App\Entity\Lottery\Draw;
use App\Factory\Entity\Lottery\DrawFactory;
use App\Repository\Lottery\DrawRepository;
use Doctrine\Persistence\ManagerRegistry;

final class GetActiveDrawService implements GetActiveDrawServiceInterface
{
    public function __construct(
        private DrawFactory $drawFactory,
        private DrawRepository $drawRepository,
    ) {
    }
    
    public function action(): Draw 
    {
        if (! $draw = $this->drawRepository->getActiveDraw()) {
            $entityManager = (new ManagerRegistry)->getManager();
            $entityManager->persist(
                $this->drawFactory->create()
            );
            $entityManager->flush();
        }
        return $draw;
    }
}