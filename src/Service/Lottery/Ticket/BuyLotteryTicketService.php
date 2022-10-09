<?php 

namespace App\Service\Lottery\Ticket;

use App\Action\Lottery\Draw\PrepareDrawLaunchingDateTimeAction;
use App\Core\Result\Result;
use App\Entity\Admin\Config;
use App\Entity\Lottery\Draw;
use App\Entity\Lottery\Ticket;
use App\Entity\Wallet;
use App\Factory\DTO\Lottery\TicketDTOFactory;
use App\Factory\Entity\Lottery\DrawFactory;
use App\Factory\Entity\Lottery\TicketFactory;
use App\Repository\Admin\ConfigRepository;
use App\Repository\Lottery\DrawRepository;
use App\Repository\Lottery\TicketRepository;
use App\Service\Lottery\Draw\GetActiveDrawService;
use App\Service\Wallet\GetWalletServiceInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

final class BuyLotteryTicketService implements BuyLotteryTicketServiceInterface
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private TicketFactory $ticketFactory,
        private ConfigRepository $configRepository,
        private DrawRepository $drawRepository,
        private GetActiveDrawService $getActiveDrawService,
        private GetWalletServiceInterface $getWalletService,
        private DrawFactory $drawFactory,
        private LoggerInterface $logger,
        private TicketDTOFactory $ticketDTOFactory,
        private ManagerRegistry $doctrine,
    ) {
    }
    
    public function action(string $wallet, int $quantity): Result|array 
    {
        if (! $this->checkIfTicketCanBePurchased()) {
            return new Result(
                code: Response::HTTP_METHOD_NOT_ALLOWED,
                message: "You can buy a lottery ticket only after the results of the drawing are announced.",
            );
        }
        if (! $wallet = $this->getWallet(address: $wallet)) {
            return new Result(
                code: Response::HTTP_UNPROCESSABLE_ENTITY,
                message: "Wallet {$wallet} not found.",
            );
        }
        $tickets = $this->storeTickets(
            wallet: $wallet, quantity: $quantity
        );
        $this->logger->info(
            message: "Tickets have been purchased {$quantity}."
        );
        $config = $this->configRepository->getConfig();
        if ($this->checkIfLotteryCanBeLunched($config)) {
            $draw = $this->lunchLottery($config);
            $this->logger->info('Draw ' . $draw->getId() . ' will be launched at ' . $draw->getLaunchedAt()->format('Y-m-d H:i') . '.');
        }
        return $tickets;
    }

    private function checkIfTicketCanBePurchased(): bool {
        if (! $this->checkIfAnyDrawExists()) {
            $this->drawRepository->save(
                entity: $this->drawFactory->create(),
                flush: true,
            );
        } 
        return $this->drawRepository->getActiveDraw() instanceof Draw;
    }

    private function checkIfAnyDrawExists(): bool {
        return $this->drawRepository->checkIfAnyDrawExists();
    }

    private function getWallet(string $address): false|Wallet {
        $wallet = $this->getWalletService->action(address: $address);
        return $wallet === null
            ? false
            : $wallet;
    }

    private function getActiveDraw(): Draw 
    {
        return $this->getActiveDrawService->action();
    }

    private function checkIfLotteryCanBeLunched(Config $config): bool 
    {
        $tickets_quantity = $this->ticketRepository->countAvailableLotteryTickets();
        return $tickets_quantity * $config->getLotteryTicketCost() >= $config->getLotteryRequiredTicketsSum();
    }

    private function lunchLottery($config): Draw {
        $draw = $this->getActiveDraw();
        $draw->setLaunchedAt(
            new DateTimeImmutable(
                (new PrepareDrawLaunchingDateTimeAction($config))->prepare()->format('Y-m-d H:i')
            )
        );
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($draw);
        $entityManager->flush();
        return $draw;
    }

    /**
     * @return TicketDTO[]
     */
    private function storeTickets(Wallet $wallet, int $quantity): array
    {
        $tickets = [];
        $draw = $this->getActiveDraw();
        for ($i = 0; $i < $quantity; $i++) {
            $ticket = $this->ticketFactory->create(
                arguments: [
                    'wallet' => $wallet,
                    'draw' => $draw,
                ]
            );
            if (!$ticket instanceof Ticket) {
                throw new UnexpectedTypeException(gettype($ticket), Ticket::class);
            }
            $this->ticketRepository->save(
                entity: $ticket, flush: true,
            );
            
            $tickets[] = $this->ticketDTOFactory->create(
                ticket: $ticket
            );
        }
        return count($tickets) > 0 
            ? $tickets
            : throw new InvalidArgumentException(
                code: Response::HTTP_BAD_REQUEST,
                message: 'Empty ticket list has been returned.'
            );
    }
}