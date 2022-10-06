<?php

namespace App\Repository\Lottery;

use App\Entity\Lottery\Draw;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Draw>
 *
 * @method Draw|null find($id, $lockMode = null, $lockVersion = null)
 * @method Draw|null findOneBy(array $criteria, array $orderBy = null)
 * @method Draw[]    findAll()
 * @method Draw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Draw::class);
    }

    public function save(Draw $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Draw $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getActiveDraw(): ?Draw
    {
        try {
            return $this->createQueryBuilder('d')
                ->where('d.is_done = :is_done')
                ->setParameter('is_done', false)
                ->orderBy('d.launched_at', 'DESC')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Throwable $e) {
            return false;
        }
    }
}
