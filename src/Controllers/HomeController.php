<?php

declare(strict_types=1);

namespace App\Controllers;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;

class HomeController
{
    public function index()
    {
        $stream = Utils::streamFor('Homepage');
        $responce = new Response();
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
