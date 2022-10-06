<?php 

namespace App\Factory\Entity;

use App\Helper\CamelCaseHelper;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;

abstract class AbstractEntityFactory
{
    protected array $required = [];

    public abstract function create(array $arguments = []): FactorableEntityInterface;

    protected function make(FactorableEntityInterface $entity, array $arguments = []) 
    {
        foreach ($this->required as $key) {
            if (!array_key_exists($key, $arguments)) {
                throw new InvalidArgumentException("Required argument is missing - {$key}.");
            }
        }
        foreach ($arguments as $key => $value) {
            $method = 'set' . CamelCaseHelper::run($key);
            if (!method_exists($entity, $method)) {
                throw new InvalidMethodNameException($method);
            }
            $entity->{$method}($value);
        }
        return $entity;
    }
}