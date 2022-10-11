<?php

namespace App\Controller\Lottery\Ticket;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Result\Result;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\Request\RequestFactory;
use App\Http\Request\Lottery\Ticket\GetTicketsViaWalletRequest;
use App\Model\DTO\Lottery\TicketDTO;
use App\Model\DTO\User\WalletDTO;
use App\Service\Lottery\Ticket\GetTicketsViaWalletServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use App\Web3\RunContract;
use Psr\Log\LoggerInterface;
use Throwable;

final class GetTicketsViaWalletController extends AbstractController
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private GetTicketsViaWalletRequest $request;

    private const CONTEXT = 'GetTicketsViaWalletController';

    public function __construct(
        private GetTicketsViaWalletServiceInterface $service,
        private LoggerInterface $logger,
    ) {
        $this->request = RequestFactory::create(
            class: GetTicketsViaWalletRequest::class
        );
    }

    #[Route('/wallet/{address}/tickets', methods: ['GET'])]
    public function action(string $address): Response
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
                    address: $address
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
        if (!$this->payload instanceof WalletDTO) {
            $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->error = 'Method should return a collection of TicketDTO.';
        } 
        return;
    }
}
