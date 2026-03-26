<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{

    public function __construct(private ResponseFactoryInterface $factory) {}

    public function index(): ResponseInterface
    {
        $stream = $this->factory->createStream('Homepage');
        $responce = $this->factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
