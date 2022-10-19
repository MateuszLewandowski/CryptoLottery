<?php

namespace App\Command;

use App\Action\Lottery\Draw\PrepareDrawLaunchingDateTimeAction;
use App\Entity\Admin\Config;
use App\Entity\Lottery\Draw;
use App\Factory\Entity\Lottery\DrawFactory;
use App\Repository\Admin\ConfigRepository;
use App\Repository\Lottery\DrawRepository;
use App\Repository\TransactionRepository;
use App\Service\Lottery\LaunchDrawServiceInterface;
use App\Service\Lottery\LogsSyncServiceInterface;
use App\Web3\BscClient;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Padam87\CronBundle\Attribute\Job;
use Throwable;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'lottery:logs-sync',
    hidden: false,
)]
#[Job(minute: '1', hour: '0', logFile: 'my-command.log')]
class LotteryLogsSyncCommand extends Command
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private LoggerInterface $logger,
        private BscClient $bscClient,
        private LogsSyncServiceInterface $logsSyncService,
        private DrawRepository $drawRepository,
        private DrawFactory $drawFactory,
        private TransactionRepository $transactionRepository,
        private ConfigRepository $configRepository,
        private LaunchDrawServiceInterface $launchDrawService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->bscClient->getLogs();
            if ($response['status'] == 1) {
                if (is_array($response['result']) && count($response['result'])) {
                    /**
                     * Check if draw exists if x > 0 available transactions exists
                     */ 
                    $draw = $this->drawRepository->getActiveDraw();
                    if (!$draw instanceof Draw) {
                        $draw = $this->drawFactory->create();
                        $this->drawRepository->save(
                            entity: $draw,
                            flush: true,
                        );
                    }
                    $is_draw_unnecessary = true;
                    /**
                     * Sync
                     */
                    foreach ($response['result'] as $transaction_meta) {
                        try {
                            $this->logsSyncService->action(
                                args: [
                                    'draw' => $draw,
                                    'block_number' => $transaction_meta['blockNumber'],
                                    'block_hash' => $transaction_meta['blockHash'],
                                    'timestamp' => $transaction_meta['timeStamp'],
                                    'hash' => $transaction_meta['hash'],
                                    'transaction_from' => $transaction_meta['from'],
                                    'transaction_to' => $transaction_meta['to'],
                                    'value' => $transaction_meta['value'],
                                    'gas' => $transaction_meta['gas'],
                                    'gas_price' => $transaction_meta['gasPrice'],
                                ]
                            );
                            $is_draw_unnecessary = false;
                        } catch (Throwable $e) {
                            continue;
                        }
                    }
                    if ($is_draw_unnecessary) {
                        $this->drawRepository->remove(
                            entity: $draw,
                            flush: true,
                        );
                    }
                    /**
                     * Check if draw can be launched.
                     */
                    $config = $this->configRepository->getConfig();
                    if ($this->checkIfDrawCanBeLaunched(draw: $draw, config: $config)) {
                        $draw->setLaunchedAt(new DateTimeImmutable());
                        $draw->setDoneAt(new DateTimeImmutable(
                            (new PrepareDrawLaunchingDateTimeAction($config))->prepare()->format(self::DATETIME_FORMAT)
                        ));
                        $this->launchDrawService->action($draw);
                    }
                    return Command::SUCCESS;
                }
            }
            return Command::FAILURE;
        } catch (Throwable $e) {
            $this->logger->critical($e->getCode() . ': ' . $e->getMessage(), ['LotteryLogsSyncCommand']);
            return Command::FAILURE;
        }
    }

    private function checkIfDrawCanBeLaunched(Draw $draw, Config $config): bool {
        return $config->getLotteryRequiredTicketsSum() < $this->transactionRepository->getActiveDrawTransactionsSum(
            date: $draw->getCreatedAt()
        );
    }
}
