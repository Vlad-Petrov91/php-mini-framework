<?php

declare(strict_types=1);

namespace Framework\Controller;

use DI\Attribute\Inject;
use Framework\Template\RendererInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class AbstractController
{
    // внедрение зависимостей через свойства класса с аттрибутом #[Inject]
    #[Inject]
    protected ResponseFactoryInterface $responseFactory;
    #[Inject]
    protected RendererInterface $renderer;
    #[Inject]
    protected StreamFactoryInterface $streamFactory;
    // конструктор удалён, так как зависимости будут внедряться через свойства класса, но в классах-наследниках можно создавать свои конструкторы для внедрения дополнительных зависимостей, если это необходимо

    protected function render(string $view, array $data = []): ResponseInterface
    {
        $content = $this->renderer->render($view, $data);
        $stream = $this->streamFactory->createStream($content ?? '');
        $responce = $this->responseFactory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }

    protected function redirect(string $path): ResponseInterface
    {
        $responce = $this->responseFactory->createResponse(302);
        return $responce->withHeader('Location', $path);
    }
}
