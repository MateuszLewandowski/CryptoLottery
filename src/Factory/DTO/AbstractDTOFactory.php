<?php 

namespace App\Factory\DTO;

use App\Entity\Lottery\Draw;
use App\Entity\Wallet;
use App\Factory\Entity\FactorableEntityInterface;
use InvalidArgumentException;
use App\Helper\CamelCaseHelper;
use DateTime;
use DateTimeImmutable;

abstract class AbstractDTOFactory
{
    protected array $required = [];

    public abstract function create(FactorableEntityInterface $object): FactorableDTOInterface;

    protected function make(string $dto, array $properties): FactorableDTOInterface
    {
        foreach ($this->required as $key) {
            if (!array_key_exists($key, $properties)) {
                throw new InvalidArgumentException("Required argument is missing - {$key}.");
            }
        }
        foreach ($properties as $key => &$value) {
            if ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
                if (str_contains($key, 'hour')) {
                    $value = $value->format('H:i');
                    continue;
                }
                $value = $value->format('Y-m-d H:i');
            }
            if ($value instanceof Wallet || $value instanceof Draw) {
                $value = [
                    'uri' => $key . '/' . $value->getId()
                ];
            }
        }
        return new $dto(
            ...array_values($properties)
        );
    }

    protected function extractEntityProperties(object $object): array {
        $collection = [];
        foreach ($this->required as $key) {
            if (!property_exists($object, $key)) {
                throw new InvalidArgumentException("Required argument is missing - {$key}.");
            }
            $collection[$key] = $object->{'get' . CamelCaseHelper::run($key)}();
        }
        return $collection;
    }
}