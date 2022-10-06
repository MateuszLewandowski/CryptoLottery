<?php

namespace App\Repository\Lottery;

use App\Entity\Lottery\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Ticket>
 *
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function save(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countAvailableLotteryTickets(): int 
    {
        try {
            return (int) $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->join('t.draw', 'd')
                ->where('d.is_done = :is_done')
                ->setParameter('is_done', false)
                ->orderBy('t.launched_at', 'DESC')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Throwable $e) {
            return false;
        }
    }
}
