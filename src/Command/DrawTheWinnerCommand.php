<?php

namespace App\Command;

use App\Entity\Lottery\Draw;
use App\Repository\Lottery\DrawRepository;
use App\Service\Lottery\GetLaunchedLotteryTransactionsServiceInterface;
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
    ) {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $draws = $this->drawRepository->getLaunchedDraws();
            if (empty($draws)) {
                $this->logger->error('Draws not found.', ['DrawTheWinnerCommand']);
                return Command::SUCCESS;
            }
            foreach ($draws as $draw) {
                $done_at = $draw->getDoneAt()->format(self::DATETIME_FORMAT);
                $now = (new DateTimeImmutable())->format(self::DATETIME_FORMAT);

                if ($now < $done_at) {
                    $this->logger->info($draw->getId() . ': Continued.', ['DrawTheWinnerCommand']);
                    continue;
                }

                $transactions = $draw->getTransactions()->filter(function($transaction) use ($draw) {
                    return $transaction->getCreatedAt() >= $draw->getLaunchedAt()->format('Y-m-d H:i:s');
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
                shuffle($args);
                $winner = $args[array_rand($args)];
                $draw->setIsDone(true);
                $draw->setWinner($winner);
                $entityManager = $this->doctrine->getManager();
                $entityManager->persist($draw);
                $entityManager->flush();
            }
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->logger->critical($e->getCode() . ': ' . $e->getMessage(), ['DrawTheWinnerCommand']);
            return Command::FAILURE;
        }
    }
}
