<?php

namespace App\Repository;

use App\Entity\Lottery\Draw;
use App\Entity\Transaction;
use App\Web3\BscClient;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    private ConfigRepository $configRepository;

    public function __construct(ManagerRegistry $registry, ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
        parent::__construct($registry, Transaction::class);
    }

    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function checkIfExists(string $hash): bool {
        try {
            return $this->createQueryBuilder('t')
                ->where('t.hash = :hash')
                ->setParameter('hash', $hash)
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult() !== null;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function getActiveDrawTransactionsSum(DateTimeImmutable $date): int 
    {
        try {
            return $this->createQueryBuilder('t')
                ->select('SUM(t.value) as sum')
                ->where('t.created_at <= :created_at')
                ->setParameter('created_at', $date->format('Y-m-d H:i:s'))
                ->andWhere('t.value >= :value')
                ->setParameter('value', $this->configRepository->getConfig()->getLotteryTicketCost())
                ->andWhere('t.transaction_to = :transaction_to')
                ->setParameter(':transaction_to', strtolower(BscClient::LOTTERY_WALLET))
                ->getQuery()
                ->getSingleScalarResult() ?? 0;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
    public function getLastLogs(): array
    {
        return $this->createQueryBuilder('t')
           ->orderBy('t.id', 'ASC')
           ->where('t.value >= :value')
           ->setParameter('value', $this->configRepository->getConfig()->getLotteryTicketCost())
           ->setMaxResults(100)
           ->getQuery()
           ->getResult()
        ;
    }

    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
    public function getLaunchedLotteryTransactions(Draw $draw): array
    {
        return $this->createQueryBuilder('t')
           ->where('t.created_at >= :created_at')
           ->where('draw_id = :draw_id')
           ->setParameter('created_at', $draw->getLaunchedAt()->format('Y-m-d H:i:s'))
           ->orderBy('t.id', 'DESC')
           ->getQuery()
           ->getResult()
        ;
    }
}
