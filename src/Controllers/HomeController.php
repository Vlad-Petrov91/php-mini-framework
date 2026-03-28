<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

class HomeController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('home/index');
    }
}
