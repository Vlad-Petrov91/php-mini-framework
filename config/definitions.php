<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Framework\Template\RendererInterface;
use Framework\Template\TwigRenderer;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

return [ // добавляем определения для интерфейсов, которые будут внедряться в контроллеры и другие классы
    ResponseFactoryInterface::class => DI\create(HttpFactory::class),
    RendererInterface::class => DI\create(TwigRenderer::class),
    StreamFactoryInterface::class => DI\create(HttpFactory::class),
    EntityManagerInterface::class => function () {
        $paths = [APP_DIR . '/src/Models']; // пути к директориям, где находятся сущности
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, true); // создаем объект конфигурации Doctrine
        $config->enableNativeLazyObjects(true); // включаем поддержку ленивых загрузок для оптимизации производительности
        $params = [
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ];
        $connection = DriverManager::getConnection($params, $config); // создаем соединение с базой данных
        return new EntityManager($connection, $config); // создаем и возвращаем EntityManager (это центральный сервис для управления всеми сущностями и их состояниями)
    },
];
