<?php 

namespace App\Trait;

use App\Helper\CamelCaseHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

trait RequestCoreValidationTrait 
{
    private function runCoreValidation(array $to_validate)
    {
        foreach ($to_validate as $key) {
            $method = 'validate' . CamelCaseHelper::run($key);
            if (method_exists($this, $method) && property_exists($this, $key)) {
                $result = self::$method($this->{$key});
                if ($result->getCode() !== Response::HTTP_OK) {
                    $result->setMessageKey($key);
                    return $result;
                }
                continue;
            }
            throw new MethodNotImplementedException(
                methodName: $method
            );
        }
        $this->is_valid = true;
    }
}