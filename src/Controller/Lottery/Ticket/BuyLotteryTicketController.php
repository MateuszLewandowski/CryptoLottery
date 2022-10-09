<?php

namespace App\Controller\Lottery\Ticket;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Http\Request\Lottery\Ticket\BuyLotteryTicketRequest;
use App\Controller\ActionableControllerInterface;
use App\Core\Result\Result;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\Request\RequestFactory;
use App\Model\DTO\Lottery\TicketDTO;
use App\Service\Lottery\Ticket\BuyLotteryTicketServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Throwable;

final class BuyLotteryTicketController extends AbstractController implements ActionableControllerInterface
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private BuyLotteryTicketRequest $request;

    private const CONTEXT = 'BuyLotteryTicketController';

    public function __construct(
        private BuyLotteryTicketServiceInterface $service,
        private LoggerInterface $logger,
    ) {
        $this->request = RequestFactory::create(
            class: BuyLotteryTicketRequest::class
        );
    }

    #[Route('/lottery/tickets/buy', methods: ['POST'])]
    public function action(): Response
    {
        try {
            /**
             * Http request validation and accurate parameters binding in the request.
             */
            $this->beforeAction();

            /**
             * Proper request handle via service
             */
            if ($this->code === Response::HTTP_OK) {
                $result = $this->service->action(
                    ...$this->request->validated()
                );
                if ($result instanceof Result) {
                    $this->code = $result->getCode();
                    $this->error = $result->getMessage();
                } else {
                    $this->payload = $result;
                    $this->afterAction();
                }
            }
        } catch (Throwable $e) {
            $this->code = $e->getCode();
            $this->error = $e->getMessage();
            /**
             * @todo Logger.
             */
        } finally {
            $this->code = ResponseCodeValidator::check(
                code: $this->code
            );
            return new Response(
                status: $this->code,
                content: json_encode(
                    $this->code !== Response::HTTP_CREATED 
                        ? ['error' => $this->error ?? $this->payload] 
                        : ['tickets' => $this->payload],
                ),
            );
        }
    }

    private function afterAction(): void {
        $this->code = Response::HTTP_CREATED;
        if (!is_array($this->payload)) {
            $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->error = 'Method should return a collection of TicketDTO.';
        } else {
            foreach ($this->payload as $ticketDTO) {
                if (!$ticketDTO instanceof TicketDTO) {
                    $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->error = 'Method should return a collection of TicketDTO.';
                    break;
                }
            }
        }
        return;
    }
}
