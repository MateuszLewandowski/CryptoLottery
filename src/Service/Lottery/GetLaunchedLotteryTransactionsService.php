<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;
use App\Factory\Entity\Lottery\TransactionFactory;
use App\Factory\DTO\Lottery\TransactionDTOFactory;
use App\Repository\TransactionRepository;
use App\Web3\BscClient;

final class GetLaunchedLotteryTransactionsService implements GetLaunchedLotteryTransactionsServiceInterface
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private TransactionFactory $transactionFactory,
        private TransactionDTOFactory $transactionDTOFactory,
        private BscClient $bscClient,
    ) {
    }
    
    public function action(Draw $draw): array 
    {
        return $this->transactionRepository->getLaunchedLotteryTransactions($draw);
    }
}