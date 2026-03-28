<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('product/index');
    }
    public function show(ServerRequestInterface $request, $args): ResponseInterface
    {
        $data['id'] = $args['id'];
        return $this->render('product/show', $data);
    }
}
