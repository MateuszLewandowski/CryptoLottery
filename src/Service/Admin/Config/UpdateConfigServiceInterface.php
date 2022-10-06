<?php 

namespace App\Service\Admin\Config;

use App\Entity\Admin\Config;

interface UpdateConfigServiceInterface 
{
    public function serve(array $args): Config;    
}