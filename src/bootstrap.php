<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use GuzzleHttp\Psr7\ServerRequest;
use HttpSoft\Emitter\SapiEmitter;
use League\Route\Router;

require dirname(__DIR__) . "/vendor/autoload.php";

$request = ServerRequest::fromGlobals();

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/product/{id:number}', [ProductController::class, 'show']);

$responce = $router->dispatch($request);
$emitter = new SapiEmitter();
$emitter->emit($responce);
