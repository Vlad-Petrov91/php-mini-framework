<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
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
$builder->addDefinitions([ // добавляем определения для интерфейсов, которые будут внедряться в контроллеры и другие классы
    ResponseFactoryInterface::class => DI\create(HttpFactory::class),
    RendererInterface::class => DI\create(TwigRenderer::class),
    StreamFactoryInterface::class => DI\create(HttpFactory::class),
    EntityManagerInterface::class => function () {
        $paths = [dirname(__DIR__) . '/src/Models']; // пути к директориям, где находятся сущности
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, true); // создаем объект конфигурации Doctrine
        $config->enableNativeLazyObjects(true); // включаем поддержку ленивых загрузок для оптимизации производительности
        $params = [
            'driver' => 'pdo_mysql',
            'host' => 'mysql',
            'dbname' => 'shop',
            'user' => 'root',
            'password' => 'root',
        ];
        $connection = DriverManager::getConnection($params, $config); // создаем соединение с базой данных
        return new EntityManager($connection, $config); // создаем и возвращаем EntityManager (это центральный сервис для управления всеми сущностями и их состояниями)
    },
]);
$builder->useAttributes(true); // Включаем поддержку атрибутов для внедрения зависимостей через свойства класса
$container = $builder->build(); // Получаем объект контейнера


$factory = new HttpFactory(); // Создаем фабрику для создания объектов PSR-7 (например, запросов и ответов), которая будет использоваться в контроллерах и других местах приложения для работы с HTTP-сообщениями
$router = new Router(); // Создаем роутер для определения маршрутов и их обработки. Роутер будет использоваться для сопоставления входящих HTTP-запросов с соответствующими контроллерами и методами, которые будут обрабатывать эти запросы. Роутер также поддерживает различные стратегии обработки маршрутов, которые можно настроить в зависимости от потребностей приложения.
$strategy = new ApplicationStrategy(); // Создаем стратегию обработки маршрутов, которая будет использоваться роутером для определения того, как обрабатывать маршруты. В данном случае мы используем ApplicationStrategy, которая позволяет использовать контейнер для внедрения зависимостей в контроллеры и другие классы, которые обрабатывают маршруты. Это означает, что при обработке маршрута роутер будет автоматически разрешать зависимости и создавать экземпляры классов, которые нужны для обработки запроса.
$strategy->setContainer($container); // Устанавливаем контейнер в стратегию обработки маршрутов, чтобы роутер мог использовать его для разрешения зависимостей при обработке маршрутов. Это позволяет нам легко внедрять зависимости в контроллеры и другие классы, которые обрабатывают маршруты, без необходимости вручную создавать экземпляры этих классов и их зависимостей. Роутер будет автоматически использовать контейнер для создания экземпляров классов и разрешения их зависимостей при обработке входящих HTTP-запросов.
$router->setStrategy($strategy); // Устанавливаем стратегию обработки маршрутов в роутер, чтобы роутер знал, какую стратегию использовать при обработке маршрутов. В данном случае мы используем ApplicationStrategy, которая позволяет использовать контейнер для внедрения зависимостей в контроллеры и другие классы, которые обрабатывают маршруты. Это означает, что при обработке маршрута роутер будет автоматически разрешать зависимости и создавать экземпляры классов, которые нужны для обработки запроса.

$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/product/{id:number}', [ProductController::class, 'show']);
$router->map(['GET', 'POST'], '/product/create', [ProductController::class, 'create']);

$responce = $router->dispatch($request); // Диспетчер маршрутов принимает входящий HTTP-запрос и пытается найти соответствующий маршрут, который соответствует методу и пути запроса. Если маршрут найден, он вызывает связанный с ним контроллер и метод, передавая ему необходимые параметры (например, идентификатор продукта). Контроллер обрабатывает запрос, выполняет бизнес-логику и возвращает HTTP-ответ, который затем отправляется обратно клиенту. Если маршрут не найден, диспетчер может вернуть ответ с ошибкой 404 или другой соответствующий ответ.
$emitter = new SapiEmitter(); // Создаем эмиттер для отправки HTTP-ответа клиенту. Эмиттер отвечает за правильную отправку заголовков и тела ответа в соответствии с PSR-7 стандартами.
$emitter->emit($responce); // Вызываем метод emit() у эмиттера, передавая ему HTTP-ответ, который был получен от диспетчера маршрутов. Эмиттер обрабатывает ответ и отправляет его клиенту, завершая обработку HTTP-запроса. Это включает в себя отправку заголовков, статуса ответа и тела ответа в соответствии с PSR-7 стандартами.
