<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use DI\Container;
use Framework\Template\TwigRenderer;
use Framework\Template\RendererInterface;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\ServerRequest;
use HttpSoft\Emitter\SapiEmitter;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

require dirname(__DIR__) . "/vendor/autoload.php";

$request = ServerRequest::fromGlobals();

$builder = new DI\ContainerBuilder(); // Создаём контейнер для возможности внедрения зависимостей через свойства класса и настраиваем его 
$builder->addDefinitions([
    ResponseFactoryInterface::class => DI\create(HttpFactory::class),
    RendererInterface::class => DI\create(TwigRenderer::class),
    StreamFactoryInterface::class => DI\create(HttpFactory::class),
]);
$builder->useAttributes(true); // Включаем поддержку атрибутов для внедрения зависимостей через свойства класса
$container = $builder->build(); // Получаем объект контейнера


$factory = new HttpFactory();
$router = new Router();
$strategy = new ApplicationStrategy();
$strategy->setContainer($container);
$router->setStrategy($strategy);

$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/product/{id:number}', [ProductController::class, 'show']);

$responce = $router->dispatch($request);
$emitter = new SapiEmitter();
$emitter->emit($responce);
