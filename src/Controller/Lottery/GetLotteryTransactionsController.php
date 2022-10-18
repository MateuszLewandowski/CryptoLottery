<?php

namespace App\Controller\Lottery;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\ActionableControllerInterface;
use App\Core\Result\Result;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\Request\RequestFactory;
use App\Http\Request\Lottery\GetLotteryTransactionsRequest;
use App\Model\DTO\Lottery\TransactionDTO;
use App\Service\Lottery\GetLotteryTransactionsServiceInterface;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use Psr\Log\LoggerInterface;
use Throwable;

final class GetLotteryTransactionsController extends AbstractController implements ActionableControllerInterface
{
    use ControllerResponseHandlerTrait;

    private GetLotteryTransactionsRequest $request;

    private const CONTEXT = 'GetLotteryTransactionsController';

    public function __construct(
        private GetLotteryTransactionsServiceInterface $service,
        private LoggerInterface $logger,
    ) {
        $this->request = RequestFactory::create(
            class: GetLotteryTransactionsRequest::class
        );
    }

    #[Route('/lottery/transactions', methods: ['GET'])]
    public function action(): Response
    {
        try {
            /**
             * Proper request handle via service
             */
            $result = $this->service->action();
            if ($result instanceof Result) {
                $this->code = $result->getCode();
                $this->error = $result->getMessage();
            } else {
                $this->payload = $result;
                $this->afterAction();
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
                    $this->code !== Response::HTTP_OK 
                        ? ['error' => $this->error ?? $this->payload] 
                        : ['transactions' => $this->payload],
                ),
            );
        }
    }

    private function afterAction(): void {
        $this->code = Response::HTTP_OK;
        if (!is_array($this->payload)) {
            $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->error = 'Method should return a collection of TransactionDTO.';
        } else {
            foreach ($this->payload as $transactionDTO) {
                if (!$transactionDTO instanceof TransactionDTO) {
                    $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->error = 'Method should return a collection of TransactionDTO.';
                    break;
                }
            }
        }
        return;
    }
}
