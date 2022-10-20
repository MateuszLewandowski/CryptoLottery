<?php

namespace App\Command;

use App\Repository\Admin\ConfigRepository;
use App\Repository\Lottery\DrawRepository;
use App\Service\Lottery\GetLaunchedLotteryTransactionsServiceInterface;
use App\Web3\BscClient;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Padam87\CronBundle\Attribute\Job;
use Psr\Log\LoggerInterface;
use Throwable;

#[AsCommand(
    name: 'lottery:draw-the-winner',
    hidden: false,
)]
#[Job(minute: '1', hour: '0', logFile: 'my-command.log')]
class DrawTheWinnerCommand extends Command
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private DrawRepository $drawRepository,
        private ManagerRegistry $doctrine,
        private LoggerInterface $logger,
        private GetLaunchedLotteryTransactionsServiceInterface $getLaunchedLotteryTransactionsService,
        private BscClient $bscClient,
        private ConfigRepository $configRepository,
    ) {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // dd(
            //     $this->bscClient->test()
            // );
            $draws = $this->drawRepository->getLaunchedDraws();
            if (empty($draws)) {
                $this->logger->error('Draws not found.', ['DrawTheWinnerCommand']);
                return Command::SUCCESS;
            }
            $config = $this->configRepository->getConfig();
            $ticket_cost = $config->getLotteryTicketCost();
            foreach ($draws as $draw) {
                $done_at = $draw->getDoneAt()->format(self::DATETIME_FORMAT);
                $now = (new DateTimeImmutable())->format(self::DATETIME_FORMAT);
                if ($now < $done_at) {
                    $this->logger->info($draw->getId() . ': Continued.', ['DrawTheWinnerCommand']);
                    continue;
                }
                $transactions = $draw->getTransactions()->filter(function($transaction) use ($ticket_cost) {
                    return $transaction->getValue() >= $ticket_cost;
                });
                if ($transactions->count() < 1) {
                    $this->logger->error($draw->getId() . ': Transactions not found.', ['DrawTheWinnerCommand']);
                    continue;
                }
                $transactions = $transactions->toArray();
                $args = [];
                foreach ($transactions as $transaction) {
                    $args[$transaction->getTransactionFrom()] = 0;
                }
                foreach ($transactions as $transaction) {
                    $args[$transaction->getTransactionFrom()] += $transaction->getValue();
                }
                $final = [];
                foreach ($args as $id => $arg) {
                    $quantity = self::countLotteryTickets($ticket_cost, $arg);
                    if ($quantity > 0) {
                        for ($i = 0; $i < $quantity; $i++) {
                            $final[] = $id;
                        }
                    }
                }
                for ($i = 0; $i < 10; $i++) {
                    shuffle($final);
                }
                $winner = $final[array_rand($final)];
                $draw->setIsDone(true);
                $draw->setWinner($winner);
                $draw->setAward(self::getAward($config->getFeeBasic(), $ticket_cost, count($final)));
                $entityManager = $this->doctrine->getManager();
                $entityManager->persist($draw);
                $entityManager->flush();
                /**
                 * @todo Pay award
                 */
            }
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->logger->critical($e->getCode() . ': ' . $e->getMessage(), ['DrawTheWinnerCommand']);
            return Command::FAILURE;
        }
    }

    private static function countLotteryTickets(int $ticket_cost, int $value): int 
    {
        return floor($value / $ticket_cost);
    }

    private static function getAward(int $fee, int $ticket_cost, int $tickets_quantity): int 
    {
        $mul = $ticket_cost * $tickets_quantity;
        $fee_value = (int) ($mul * ($fee / 100));
        return $mul - $fee_value;
    }
}
