<?php

declare(strict_types=1);

namespace App\Controllers;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;

class ProductController
{
    public function index()
    {
        $stream = Utils::streamFor('All products');
        $responce = new Response();
        $responce = $responce->withBody($stream);
        return $responce;
    }
    public function show($responce, $args)
    {
        $id = $args['id'];
        $stream = Utils::streamFor('One Product id ' . $id);
        $responce = new Response();
        $responce = $responce->withBody($stream);
        return $responce;
    }
}
