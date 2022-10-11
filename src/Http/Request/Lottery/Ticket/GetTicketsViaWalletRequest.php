<?php 

namespace App\Http\Request\Lottery\Ticket;

use App\Core\Result\Result;
use App\Http\Request\ValidatableRequestInterface;
use App\Trait\RequestAPITokenValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetTicketsViaWalletRequest extends Request implements ValidatableRequestInterface
{
    use RequestAPITokenValidationTrait;
    
    public function validate(): Result 
    {
        if (false === self::validateAPIToken(
            api_token: $this->headers->get('API-Token', false)
        )) {
            return new Result(
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorized.',
            );
        }
        
        return new Result(
            code: Response::HTTP_OK
        );
    }
}