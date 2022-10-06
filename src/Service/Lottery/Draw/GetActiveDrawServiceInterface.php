<?php 

namespace App\Service\Lottery\Draw;

use App\Entity\Lottery\Draw;

interface GetActiveDrawServiceInterface
{
    public function action(): Draw;
}