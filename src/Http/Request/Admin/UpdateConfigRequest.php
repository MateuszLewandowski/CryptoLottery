<?php 

namespace App\Http\Request\Admin;

use App\Core\Result\Result;
use App\Helper\MathFloatConvertHelper;
use App\Http\Middleware\Rule\IsBoolean;
use App\Http\Middleware\Rule\IsFloat;
use App\Http\Middleware\Rule\IsInteger;
use App\Http\Middleware\Rule\IsNotNull;
use App\Http\Middleware\Rule\IsPositive;
use App\Http\Middleware\Rule\IsPositiveOrZero;
use App\Http\Middleware\Rule\IsString;
use App\Http\Middleware\Rule\IsTime;
use App\Http\Request\ValidatableRequestInterface;
use App\Trait\RequestAPITokenValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Trait\RequestCoreValidationTrait;
use ErrorException;
use Symfony\Component\HttpFoundation\Response;

final class UpdateConfigRequest extends Request implements ValidatableRequestInterface
{
    use RequestCoreValidationTrait, RequestAPITokenValidationTrait;

    public ?string $draw_begins_at_hour;
    public ?int $draw_begins_at_day_no;
    public ?int $draw_begins_at_concrete_day;
    public ?bool $draw_is_concrete_day_set;
    public ?int $lottery_ticket_cost;
    public ?int $lottery_required_tickets_sum;
    public ?int $fee_basic;

    private bool $is_valid = false;

    private const TO_VALIDATE = [
        'draw_begins_at_hour',
        'draw_begins_at_day_no',
        'draw_begins_at_concrete_day',
        'draw_is_concrete_day_set',
        'lottery_ticket_cost',
        'lottery_required_tickets_sum',
        'fee_basic',
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
        $this->draw_begins_at_hour = $this->request->get('draw_begins_at_hour');
        $this->draw_begins_at_day_no = $this->request->get('draw_begins_at_day_no');
        $this->draw_begins_at_concrete_day = $this->request->get('draw_begins_at_concrete_day');
        $this->draw_is_concrete_day_set = filter_var($this->request->get('draw_is_concrete_day_set'), FILTER_VALIDATE_BOOLEAN);
        $this->lottery_ticket_cost = (int) $this->request->get('lottery_ticket_cost');
        $this->lottery_required_tickets_sum = (int) $this->request->get('lottery_required_tickets_sum');
        $this->fee_basic = (float) $this->request->get('fee_basic');
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
            'draw_begins_at_hour' => $this->draw_begins_at_hour, 
            'draw_begins_at_day_no' => $this->draw_begins_at_day_no, 
            'draw_begins_at_concrete_day' => $this->draw_begins_at_concrete_day,
            'draw_is_concrete_day_set' => $this->draw_is_concrete_day_set, 
            'lottery_ticket_cost' => $this->lottery_ticket_cost,
            'lottery_required_tickets_sum' => $this->lottery_required_tickets_sum, 
            'fee_basic' => $this->fee_basic, 
        ];
    }

    private static function validateDrawBeginsAtHour($draw_begins_at_hour): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsString)
            ->set(new IsTime);
        return $isNotNull->validate(value: $draw_begins_at_hour);
    }

    private static function validateDrawBeginsAtDayNo($draw_begins_at_day_no): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsInteger)
            ->set(new IsPositive);
        return $isNotNull->validate(value: $draw_begins_at_day_no);
    }

    private static function validateDrawBeginsAtConcreteDay($draw_begins_at_concrete_day): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsInteger)
            ->set(new IsPositive);
        return $isNotNull->validate(value: $draw_begins_at_concrete_day);
    }

    private static function validateDrawIsConcreteDaySet($draw_is_concrete_day_set): Result 
    {
        $isNotNull = new IsBoolean;
        return $isNotNull->validate(value: $draw_is_concrete_day_set);
    }

    private static function validateLotteryTicketCost($lottery_ticket_cost): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsInteger)
            ->set(new IsPositive);
        return $isNotNull->validate(value: $lottery_ticket_cost);
    }

    private static function validateLotteryRequiredTicketsSum($lottery_required_tickets_sum): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsInteger)
            ->set(new IsPositive);
        return $isNotNull->validate(value: $lottery_required_tickets_sum);
    }

    private static function validateFeeBasic($fee_basic): Result 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->set(new IsFloat)
            ->set(new IsPositiveOrZero);
        return $isNotNull->validate(value: $fee_basic);
    }
}