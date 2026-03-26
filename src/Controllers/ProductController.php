<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ProductController
{

    public function __construct(private ResponseFactoryInterface $factory) {}
    public function index(): ResponseInterface
    {

        $stream = $this->factory->createStream('All products');
        $responce = $this->factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
    public function show(ServerRequestInterface $request, $args): ResponseInterface
    {
        $id = $args['id'];
        $stream = $this->factory->createStream('One Product id ' . $id);
        $responce = $this->factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
