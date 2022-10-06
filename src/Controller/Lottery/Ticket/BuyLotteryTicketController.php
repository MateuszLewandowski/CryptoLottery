<?php

namespace App\Controller\Lottery\Ticket;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Http\Request\Lottery\Ticket\BuyLotteryTicketRequest;
use App\Controller\ActionableControllerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\Request\RequestFactory;
use App\Service\Lottery\Ticket\BuyLotteryTicketServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use Throwable;

final class BuyLotteryTicketController extends AbstractController implements ActionableControllerInterface
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private BuyLotteryTicketRequest $request;

    public function __construct(
        private BuyLotteryTicketServiceInterface $service,
    ) {
        $this->request = RequestFactory::create(
            class: BuyLotteryTicketRequest::class
        );
    }

    #[Route('/lottery/ticket/buy', methods: ['POST'])]
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
            [$this->code, $this->payload] = $this->service->action(
                wallet: $this->wallet, quantity: $this->quantity
            );

            

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
}
