<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Template\RendererInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class HomeController
{

    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private RendererInterface $renderer,
        private StreamFactoryInterface $streamFactory,
    ) {}
    public function index(): ResponseInterface
    {
        $content = $this->renderer->render('home/index');
        // $stream = $this->factory->createStream($content);
        $stream = $this->streamFactory->createStream($content ?? '');
        $response = $this->responseFactory->createResponse(200);
        $response = $response->withBody($stream);
        return $response;
    }
}
