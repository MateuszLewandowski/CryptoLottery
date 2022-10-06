<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

interface ActionableControllerInterface
{
    public function action(): Response;
}