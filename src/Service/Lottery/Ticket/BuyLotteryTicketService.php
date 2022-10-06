<?php 

namespace App\Service\Lottery\Ticket;

use App\Action\Lottery\Draw\PrepareDrawLaunchingDateTimeAction;
use App\Core\Result\Result;
use App\Entity\Lottery\Draw;
use App\Entity\Lottery\Ticket;
use App\Entity\Wallet;
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

final class BuyLotteryTicketService implements BuyLotteryTicketServiceInterface
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private TicketFactory $ticketFactory,
        private ConfigRepository $configRepository,
        private DrawRepository $drawRepository,
        private GetActiveDrawService $getActiveDrawService,
        private GetWalletServiceInterface $getWalletService,
    ) {
    }
    
    public function action(string $wallet, int $quantity): Result 
    {
        if (! $this->checkIfTicketCanBePurchased()) {
            return new Result(
                code: Response::HTTP_METHOD_NOT_ALLOWED,
                message: "You can buy a lottery ticket only after the results of the drawing are announced.",
            );
        }
        if (! $wallet = $this->getWallet(wallet: $wallet)) {
            return new Result(
                code: Response::HTTP_UNPROCESSABLE_ENTITY,
                message: "Wallet {$wallet} not found.",
            );
        }
        $tickets = $this->storeTickets(wallet: $wallet, quantity: $quantity);
        
        if ($this->checkIfLotteryCanBeLunched()) {
            $this->lunchLottery();
        }

        return $tickets;
    }

    private function checkIfTicketCanBePurchased(): bool {
        return $this->drawRepository->checkIfTicketCanBePurchased();
    }

    private function getWallet(string $wallet): false|Wallet {
        $wallet = $this->getWalletService->action(wallet: $wallet);
        return $wallet === null
            ? false
            : $wallet;
    }

    private function getActiveDraw(): Draw 
    {
        return $this->getActiveDrawService->action();
    }

    private function checkIfLotteryCanBeLunched(): bool 
    {
        $config = $this->configRepository->getConfig();
        $tickets_quantity = $this->ticketRepository->countAvailableLotteryTickets();
        return $tickets_quantity * $config->getLotteryTicketCost() >= $config->getLotteryRequiredTicketsSum();
    }

    private function lunchLottery(): void {
        $draw = $this->getActiveDraw();
        $doctrine = new ManagerRegistry;
        $draw->setLauchedAt(
            new DateTimeImmutable(
                (new PrepareDrawLaunchingDateTimeAction(
                    new ConfigRepository(
                        $doctrine
                    )
                ))->prepare()
            )
        );
        $entityManager = $doctrine->getManager();
        $entityManager->persist($draw);
        $entityManager->flush();
        return;
    }

    /**
     * @return Ticket[]
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
            $tickets[] = $ticket;
        }
        return count($tickets) > 0 
            ? $tickets
            : throw new InvalidArgumentException(
                code: Response::HTTP_BAD_REQUEST,
                message: 'Empty ticket list has been returned.'
            );
    }
}