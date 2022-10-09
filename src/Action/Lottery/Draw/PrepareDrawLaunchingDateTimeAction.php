<?php 

namespace App\Action\Lottery\Draw;

use App\Action\PrepareableActionInterface;
use App\Entity\Admin\Config;
use App\Model\Enum\Weekday;
use App\Repository\Admin\ConfigRepository;
use DateTime;
use DateTimeImmutable;

final class PrepareDrawLaunchingDateTimeAction implements PrepareableActionInterface
{
    public function __construct(
        private Config $config,
    ) {
    }

    public function prepare(mixed $value = null): mixed 
    {
        $date = $this->config->getDrawIsConcreteDaySet()
            ? new DateTime(
                "next " . Weekday::name(
                    $this->config->getDrawBeginsAtConcreteDay()
                )
            )
            : new DateTime(
                "now + " . $this->config->getDrawBeginsAtDayNo() . ' days'
            );
        return new DateTimeImmutable(
            $date->setTime(
                ...explode(
                    separator: ':',
                    string: $this->config->getDrawBeginsAtHour()->format('H:i')
                )
            )->format('Y-m-d H:i')
        );
    }
}