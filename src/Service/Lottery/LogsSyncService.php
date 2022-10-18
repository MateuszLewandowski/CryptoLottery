<?php 

namespace App\Service\Lottery;

use App\Factory\Entity\Lottery\TransactionFactory;
use App\Repository\TransactionRepository;
use App\Core\Result\Result;
use App\Web3\BscClient;

final class LogsSyncService implements LogsSyncServiceInterface
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private TransactionFactory $transactionFactory,
        private BscClient $bscClient,
    ) {
    }
    
    public function action(array $args): array|Result 
    {
        $this->transactionRepository->save(
            entity: $this->transactionFactory->create(
                arguments: $args,
            ),
            flush: true,
        );
        return [];
    }
}