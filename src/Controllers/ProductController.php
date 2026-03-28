<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Template\Renderer;
use Framework\Template\RendererInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ProductController
{

    public function __construct(
        private ResponseFactoryInterface $factory,
        private RendererInterface $renderer,
        private StreamFactoryInterface $streamFactory
    ) {}
    public function index(): ResponseInterface
    {

        $content = $this->renderer->render('product/index');
        $stream = $this->streamFactory->createStream($content ?? '');
        $responce = $this->factory->createResponse();
        $responce = $responce->withBody($stream);
        return $responce;
    }
    public function show(ServerRequestInterface $request, $args): ResponseInterface
    {
        $id = $args['id'];
        $content = $this->renderer->render('product/show', ['id' => $id]);
        $stream = $this->streamFactory->createStream($content ?? '');
        $responce = $this->factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
