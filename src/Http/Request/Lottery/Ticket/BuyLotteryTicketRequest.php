<?php 

namespace App\Http\Request\Lottery\Ticket;

use App\Core\Result\Result;
use App\Http\Middleware\Rule\IsNotNull;
use App\Http\Middleware\Rule\IsPositive;
use App\Http\Middleware\Rule\IsString;
use App\Http\Request\ValidatableRequestInterface;
use App\Trait\RequestCoreValidationTrait;
use ErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BuyLotteryTicketRequest extends Request implements ValidatableRequestInterface
{
    use RequestCoreValidationTrait;

    public string $wallet;
    public int $quantity;

    private bool $is_valid = false;

    private const TO_VALIDATE = [
        'wallet',
        'quantity',
    ];

    public function validate(): Result 
    {
        $this->wallet = $this->request->get('wallet');
        $this->quantity = $this->request->get('quantity');

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