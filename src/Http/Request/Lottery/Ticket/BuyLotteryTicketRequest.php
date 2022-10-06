<?php 

namespace App\Http\Request\Lottery\Ticket;

use App\Core\Result\Result;
use App\Entity\Wallet;
use App\Http\Middleware\Rule\IsNotNull;
use App\Http\Middleware\Rule\IsPositive;
use App\Http\Middleware\Rule\IsString;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

final class BuyLotteryTicketRequest extends Request implements ValidatableRequestInterface
{
    public string $wallet;
    public int $quantity;

    public function validate(): Result 
    {
        $this->wallet = $this->request->get('wallet');
        $this->quantity = $this->request->get('quantity', -1);

        foreach (get_class_vars($this) as $property) {
            $method = 'validate' . ucfirst($property);
            if (method_exists($this, $method)) {
                $result = self::$method($this->property);
                if ($result->getCode() !== Response::HTTP_OK) {
                    return $result;
                }
                continue;
            }
            throw new MethodNotImplementedException(
                methodName: $method
            );
        }
        return new Result(
            code: Response::HTTP_OK
        );
    }

    private static function validateWallet(Wallet $wallet): Result
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