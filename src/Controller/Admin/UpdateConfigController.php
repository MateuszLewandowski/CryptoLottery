<?php 

namespace App\Controller\Admin;

use App\Controller\ActionableControllerInterface;
use App\Factory\Request\RequestFactory;
use App\Http\Exception\LoggerBuilder;
use App\Http\Request\Admin\UpdateConfigRequest;
use App\Service\Admin\Config\UpdateConfigServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class UpdateConfigController extends AbstractController implements ActionableControllerInterface
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private UpdateConfigRequest $request;
    
    private const CONTEXT = 'UpdateConfigController';

    public function __construct(
        private UpdateConfigServiceInterface $service,
        private LoggerInterface $logger,
    ) {
        $this->request = RequestFactory::create(
            class: UpdateConfigRequest::class
        );
    }

    #[Route('/config', methods: ['POST', 'PUT', 'PATCH'])]
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
                $this->payload = $this->service->serve(
                    $this->request->validated()
                );
            }

        } catch (Throwable $e) {
            $this->code = $e->getCode();
            $this->error = $e->getMessage();
            $this->logger->error(
                message: LoggerBuilder::handle($e),
                context: [self::CONTEXT],
            );
        } finally {
            $this->code = ResponseCodeValidator::check(
                code: $this->code
            );
            return new Response(
                status: $this->code,
                content: json_encode(
                    $this->code !== Response::HTTP_CREATED && $this->code !== Response::HTTP_OK 
                        ? ['error' => $this->error ?? $this->payload] 
                        : ['config' => $this->payload],
                ),
            );
        }
    }
}