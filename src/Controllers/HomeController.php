<?php

declare(strict_types=1);

namespace App\Controllers;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    public function index(): ResponseInterface
    {
        $factory = new HttpFactory;
        $stream = $factory->createStream('Homepage');
        $responce = $factory->createResponse(200);
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
