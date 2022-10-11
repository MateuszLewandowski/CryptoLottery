<?php

namespace App\Controller\Wallet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Result\Result;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Factory\Request\RequestFactory;
use App\Http\Request\Wallet\GetWalletCryptocurrencyEthRequest;
use App\Service\Wallet\GetWalletCryptocurrencyEthServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use Psr\Log\LoggerInterface;
use Throwable;
use App\Web3\Validation\WalletAddressValidation;

final class GetWalletCryptocurrencyEthController extends AbstractController
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private GetWalletCryptocurrencyEthRequest $request;

    private const CONTEXT = 'GetUserWalletCryptocurrencyEthController';

    public function __construct(
        private GetWalletCryptocurrencyEthServiceInterface $service,
        private LoggerInterface $logger,
    ) {
        $this->request = RequestFactory::create(
            class: GetWalletCryptocurrencyEthRequest::class
        );
    }

    #[Route('/wallet/{address}/cryptocurrency/eth', methods: ['GET'])]
    public function action(string $address): Response
    {
        try {
            if (! WalletAddressValidation::check(address: $address)) {
                $this->code = Response::HTTP_BAD_REQUEST;
                $this->error = 'Invalid wallet address.';
            } else {
                /**
                 * Http request validation and accurate parameters binding in the request.
                 */
                $this->beforeAction();
            }

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
                    $this->code = Response::HTTP_OK;
                    $this->payload = $result;
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
                    $this->code !== Response::HTTP_OK 
                        ? ['error' => $this->error ?? $this->payload] 
                        : ['cryptocurrency' => $this->payload],
                ),
            );
        }
    }
}
