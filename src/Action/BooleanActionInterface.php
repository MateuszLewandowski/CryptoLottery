<?php 

namespace App\Action;

interface BooleanActionInterface
{
    public function run(mixed $value): bool;
}