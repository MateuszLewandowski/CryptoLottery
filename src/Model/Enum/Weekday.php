<?php 

namespace App\Model\Enum;

enum Weekday: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public static function name(int $id): string 
    {
        return (match ($id) {
                1 => self::MONDAY,
                2 => self::TUESDAY,
                3 => self::WEDNESDAY,
                4 => self::THURSDAY,
                5 => self::FRIDAY,
                6 => self::SATURDAY,
                7 => self::SUNDAY,
        })
        ->value;
    }
}