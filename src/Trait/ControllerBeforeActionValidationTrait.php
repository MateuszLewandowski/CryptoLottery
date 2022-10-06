<?php 

namespace App\Trait;

use Symfony\Component\HttpFoundation\Response;

trait ControllerBeforeActionValidationTrait 
{
    private function beforeAction(): void {
        $result = $this->request->validate();
        $this->code = $result->getCode();
        if ($this->code !== Response::HTTP_OK) {
            $this->error = $result->getMessage();
            return;
        }
        $this->payload = $result->getMessage();
    }
}