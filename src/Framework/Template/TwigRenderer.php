<?php

namespace Framework\Template;

class TwigRenderer implements RendererInterface
{
    public function render(string $view, array $data = []): string
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 3) . "/views//");
        $twig = new \Twig\Environment($loader, [
            // 'cache' => '/path/to/compilation_cache', // отключил кэш для разработки
        ]);
        // $data['content'] = $twig->render("{$view}.twig", $data); // если нужно рендерить шаблон внутри шаблона
        return $twig->render("{$view}.twig", $data);
    }
}
