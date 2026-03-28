<?php

namespace Framework\Template;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Renderer implements RendererInterface
{
    public function render(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        include dirname(__DIR__, 3) . "/views/{$view}.php";
        return ob_get_clean();
    }
}
