<?php 

namespace App\Service\Lottery;

use App\Core\Result\Result;

interface LogsSyncServiceInterface
{
    public function action(array $args): void;
}