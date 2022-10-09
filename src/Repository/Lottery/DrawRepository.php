<?php

namespace App\Repository\Lottery;

use App\Entity\Lottery\Draw;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
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
                ->AndWhere('d.launched_at is null')
                ->setParameter('is_done', false)
                ->orderBy('d.created_at', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable $e) {
            return null;
        }
    }

    public function getLaunchedDraw(): ?Draw
    {
        try {
            return $this->createQueryBuilder('d')
                ->where('d.is_done = :is_done')
                ->AndWhere('d.launched_at is not null')
                ->setParameter('is_done', false)
                ->orderBy('d.created_at', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable $e) {
            return null;
        }
    }

    public function checkIfAnyDrawExists(): bool 
    {
        try {
            return $this->createQueryBuilder('d')
                ->select('COUNT(d.id) as quantity')
                ->getQuery()
                ->getSingleScalarResult() > 0;
        } catch (Throwable $e) {
            dd($e);
            return false;
        } 
    }
}
