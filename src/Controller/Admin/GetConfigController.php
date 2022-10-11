<?php 

namespace App\Controller\Admin;

use App\Controller\ActionableControllerInterface;
use App\Factory\DTO\Admin\ConfigDTOFactory;
use App\Factory\Entity\Admin\ConfigFactory;
use App\Factory\Request\RequestFactory;
use App\Http\Request\Admin\GetConfigRequest;
use App\Service\Admin\Config\GetConfigServiceInterface;
use App\Trait\ControllerBeforeActionValidationTrait;
use App\Trait\ControllerResponseHandlerTrait;
use App\Validation\ResponseCodeValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class GetConfigController extends AbstractController implements ActionableControllerInterface
{
    use ControllerResponseHandlerTrait, ControllerBeforeActionValidationTrait;

    private GetConfigRequest $request;

    public function __construct(
        private GetConfigServiceInterface $service,
        private ConfigDTOFactory $configDTOFactory,
    ) {
        $this->request = RequestFactory::create(
            class: GetConfigRequest::class
        );
    }

    #[Route('/config', methods: ['GET'])]
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
            $this->payload = $this->service->serve();

            if ($this->payload === null) {
                $this->code = Response::HTTP_NOT_FOUND;
                $this->error = 'Config not found';
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
                        : ['config' => $this->payload],
                ),
            );
        }
    }
}