<?php 

namespace App\Service\Admin\Config;

use App\Model\DTO\Admin\ConfigDTO;

interface UpdateConfigServiceInterface 
{
    public function serve(array $args): ConfigDTO;    
}