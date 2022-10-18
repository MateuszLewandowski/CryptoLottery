<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;

interface LaunchDrawServiceInterface
{
    public function action(Draw $draw): void;
}