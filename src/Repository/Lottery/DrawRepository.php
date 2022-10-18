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

    public function update(Draw $draw): void {
        try {
            $this->createQueryBuilder('d')
                ->update(Draw::class, 'd')
                ->where('d.id = :id')
                ->setParameter('id', $draw->getId())
                ->set('d.launched_at', ':launched_at')
                ->set('d.done_at', ':done_at')
                ->setParameter('launched_at', $draw->getLaunchedAt())
                ->setParameter('done_at', $draw->getDoneAt())
                ->getQuery()
                ->execute();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function checkIfDrawCanBeLaunched(): bool 
    {
        try {
            return $this->getActiveDraw() instanceof Draw;
        } catch (Throwable $e) {

        }
    }

    public function getActiveDraw(): ?Draw
    {
        try {
            return $this->createQueryBuilder('d')
                ->where('d.launched_at is null')
                ->AndWhere('d.done_at is null')
                ->orderBy('d.created_at', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function getDoneDraws(): array {
        try {
            return $this->createQueryBuilder('d')
                ->where('d.is_done = :is_done')
                ->setParameter(':is_done', true)
                ->orderBy('d.created_at', 'DESC')
                ->getQuery()
                ->getResult();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function getLaunchedDraws(): ?array
    {
        try {
            return $this->createQueryBuilder('d')
                ->where('d.launched_at is not null')
                ->andWhere('d.is_done = :is_done')
                ->setParameter('is_done', false)
                ->orderBy('d.created_at', 'DESC')
                ->getQuery()
                ->getResult();
        } catch (Throwable $e) {
            throw $e;
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
            return false;
        } 
    }

    public function getAll(): array {
        try {
            return $this->createQueryBuilder('d')
                ->getQuery()
                ->getResult();
        } catch (Throwable $e) {
            throw $e;
        } 
    }
}
