<?php 

namespace App\Controller\Authorization;

use App\Controller\ActionableControllerInterface;
use App\Controller\FactorableRequestControllerInterface;
use App\Factory\Request\RequestFactory;
use App\Http\Request\Authorization\SignInRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class SignInController extends AbstractController implements ActionableControllerInterface
{
    private SignInRequest $request;

    public function __construct(
        // service
    ) {
        $this->request = RequestFactory::create(
            class: SignInRequest::class
        );
    }

    #[Route('/auth/sign-in', methods: ['POST'])]
    public function action(): Response
    {
        try {

        } catch (Throwable $e) {

        } finally {

        }
    }
}