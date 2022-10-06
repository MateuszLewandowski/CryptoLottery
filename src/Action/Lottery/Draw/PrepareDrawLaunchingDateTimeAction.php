<?php 

namespace App\Action\Lottery\Draw;

use App\Action\PrepareableActionInterface;
use App\Model\Enum\Weekday;
use App\Repository\Admin\ConfigRepository;
use DateTime;
use DateTimeImmutable;

final class PrepareDrawLaunchingDateTimeAction implements PrepareableActionInterface
{
    public function __construct(
        private ConfigRepository $configRepository
    ) {
    }

    public function prepare(mixed $value = null): mixed 
    {
        $config = $this->configRepository->getConfig();
        $date = $config->getDrawIsConcreteDaySet()
            ? new DateTime(
                "next " . Weekday::name(
                    $config->getDrawBeginsAtConcreteDay()
                )
            )
            : new DateTime(
                "now + " . $config->getDrawBeginsAtDayNo() . ' days'
            );
        return new DateTimeImmutable(
            $date->setTime(
                ...explode(
                    separator: ':',
                    string: $config->getDrawBeginsAtHour()->format('H:i')
                )
            )->format('Y-m-d H:i')
        );
    }
}