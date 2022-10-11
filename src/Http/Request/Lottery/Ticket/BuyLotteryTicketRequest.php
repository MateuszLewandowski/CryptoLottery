<?php 

namespace App\Http\Request\Lottery\Ticket;

use App\Core\Result\Result;
use App\Http\Middleware\Rule\IsNotNull;
use App\Http\Middleware\Rule\IsPositive;
use App\Http\Middleware\Rule\IsString;
use App\Http\Request\ValidatableRequestInterface;
use App\Trait\RequestAPITokenValidationTrait;
use App\Trait\RequestCoreValidationTrait;
use App\Web3\Validation\WalletAddressValidation;
use ErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BuyLotteryTicketRequest extends Request implements ValidatableRequestInterface
{
    use RequestCoreValidationTrait, RequestAPITokenValidationTrait;

    public string $wallet;
    public int $quantity;

    private bool $is_valid = false;

    private const TO_VALIDATE = [
        'wallet',
        'quantity',
    ];

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

        $this->wallet = $this->request->get('wallet');
        $this->quantity = $this->request->get('quantity');

        if (! WalletAddressValidation::check(address: $this->wallet)) {
            $this->code = Response::HTTP_BAD_REQUEST;
            $this->error = 'Invalid wallet address.';
        }

        $this->runCoreValidation(
            to_validate: self::TO_VALIDATE
        );

        return new Result(
            code: Response::HTTP_OK
        );
    }

    public function validated(): array 
    {
        if (!$this->is_valid) {
            throw new ErrorException(
                message: 'The request was not validated or an error has occurred.',
                code: Response::HTTP_BAD_REQUEST
            );
        }
        return [
            'wallet' => $this->wallet, 
            'quantity' => $this->quantity, 
        ];
    }

    private static function validateWallet(string $wallet): Result
    {
        $isNotNull = new IsNotNull;
        $isNotNull->set(new IsString);
        return $isNotNull->validate(value: $wallet);
    }

    private static function validateQuantity(int $quantity): Result
    {
        $isNotNull = new IsNotNull;
        $isNotNull->set(new IsPositive);
        return $isNotNull->validate(value: $quantity);
    }
}