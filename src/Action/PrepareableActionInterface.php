<?php 

namespace App\Action;

interface PrepareableActionInterface
{
    public function prepare(mixed $value = null): mixed;
}