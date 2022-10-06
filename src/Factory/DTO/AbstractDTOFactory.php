<?php 

namespace App\Factory\DTO;

use App\Factory\Entity\FactorableEntityInterface;
use Symfony\Component\HttpFoundation\Response;
use DeepCopy\Exception\PropertyException;
use InvalidArgumentException;

abstract class AbstractDTOFactory
{
    protected array $required = [];

    public abstract function create(FactorableEntityInterface $object): FactorableDTOInterface;

    protected function make(string $DTO, object $object) 
    {
        foreach ($this->required as $key) {
            if (!array_key_exists($key, $arguments)) {
                throw new InvalidArgumentException("Required argument is missing - {$key}.");
            }
        }
        foreach ($arguments as $key => $value) {
            if (!property_exists($DTO, $key)) {
                throw new PropertyException(
                    code: Response::HTTP_INTERNAL_SERVER_ERROR,
                    message: "Required property {$key} not found."
                );
            }
        }
        return $DTO(...array_values($arguments));
    }
}