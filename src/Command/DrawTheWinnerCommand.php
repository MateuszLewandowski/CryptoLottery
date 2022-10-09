<?php

namespace App\Command;

use App\Entity\Lottery\Draw;
use App\Repository\Lottery\DrawRepository;
use App\Repository\Lottery\TicketRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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
    private const DATETIME_FORMAT = 'Y-m-d H:i';

    public function __construct(
        private TicketRepository $ticketRepository,
        private DrawRepository $drawRepository,
        private ManagerRegistry $doctrine,
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $draw = $this->drawRepository->getLaunchedDraw();
            if (!$draw instanceof Draw) {
                $this->logger->error('Draw not found.', ['DrawTheWinnerCommand']);
                return Command::FAILURE;
            }
            $launched_at = $draw->getLaunchedAt()->format(self::DATETIME_FORMAT);
            $now = (new DateTimeImmutable())->format(self::DATETIME_FORMAT);
            if ($now < $launched_at) {
                $this->logger->info('Continued.', ['DrawTheWinnerCommand']);
                return Command::SUCCESS;
            }
            $tickets = $this->ticketRepository->getLaunchedLotteryTickets();
            if (count($tickets) < 1) {
                $this->logger->error('Tickets not found.', ['DrawTheWinnerCommand']);
                return Command::INVALID;
            }
            shuffle($tickets);
            $winner = $tickets[array_rand($tickets)];
            $draw->setWinner($winner);
            $draw->setIsDone(true);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($draw);
            $entityManager->flush();
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), ['DrawTheWinnerCommand']);
            return Command::FAILURE;
        }
    }
}
