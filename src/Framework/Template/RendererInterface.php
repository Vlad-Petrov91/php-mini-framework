<?php

namespace Framework\Template;

interface RendererInterface
{
    public function render(string $view, array $data = []): string;
}
