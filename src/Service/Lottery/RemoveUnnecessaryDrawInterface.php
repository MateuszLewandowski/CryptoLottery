<?php 

namespace App\Service\Lottery;

use App\Entity\Lottery\Draw;

interface RemoveUnnecessaryDrawInterface
{
    public function action(Draw $draw): void;
}