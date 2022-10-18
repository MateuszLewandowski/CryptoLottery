<?php 

namespace App\Service\Lottery;

use App\Factory\Entity\Lottery\TransactionFactory;
use App\Factory\DTO\Lottery\TransactionDTOFactory;
use App\Repository\TransactionRepository;
use App\Core\Result\Result;
use App\Web3\BscClient;
use Symfony\Component\HttpFoundation\Response;

final class GetLotteryTransactionsService implements GetLotteryTransactionsServiceInterface
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private TransactionFactory $transactionFactory,
        private TransactionDTOFactory $transactionDTOFactory,
        private BscClient $bscClient,
    ) {
    }
    
    public function action(): array|Result 
    {
        $payload = $this->transactionRepository->getLastLogs();
        if (count($payload) > 0) {
            $result = [];
            foreach ($payload as $transaction) {
                $result[] = $this->transactionDTOFactory->create(
                    transaction: $transaction
                );
            }
            return $result;
        }
        return new Result(
            code: Response::HTTP_NOT_FOUND,
            message: 'Logs not found.'
        );
    }
}