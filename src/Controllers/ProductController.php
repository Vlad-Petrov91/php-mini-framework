<?php

declare(strict_types=1);

namespace App\Controllers;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ProductController
{
    public function index(): ResponseInterface
    {
        $factory = new HttpFactory;
        $stream = $factory->createStream('All products');
        $responce = $factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
    public function show(ServerRequestInterface $request, $args): ResponseInterface
    {
        $id = $args['id'];
        $factory = new HttpFactory;
        $stream = $factory->createStream('One Product id ' . $id);
        $responce = $factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
