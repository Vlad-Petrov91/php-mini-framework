<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function index()
    {
        try {
            $repo = $this->entityManager->getRepository(Product::class); // получаем репозиторий для сущности Product (он предоставляет методы для извлечения сущностей из базы данных в виде объектов)
            $products = $repo->findAll(); // извлекаем все продукты из базы данных
            return $this->render('product/index', ['products' => $products]); // передаем массив продуктов в шаблон для отображения
        } catch (\Exception $e) {
            // обработка исключений, например, логирование ошибки и отображение страницы с сообщением об ошибке
            // error_log($e->getMessage());
            return $this->render('error', ['message' => $e->getMessage()]); // отображаем страницу с сообщением об ошибке
        }
    }
    public function show(ServerRequestInterface $request, $args): ResponseInterface
    {
        $product = $this->entityManager->find(Product::class, $args['id']); // извлекаем продукт по его идентификатору (id) из базы данных
        return $this->render('product/show', ['product' => $product]);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $requestData = $request->getParsedBody(); // извлекаем данные из запроса (аналог супер глобального массива $_POST)
            $product = new Product; // создаем новый объект продукта
            $product->setName($requestData['name'] ?? ''); // устанавливаем имя продукта из данных запроса (или пустую строку, если имя не было передано)
            $product->setPrice((int)($requestData['price'] ?? 0)); // устанавливаем цену продукта из данных запроса (или 0, если цена не была передана)
            $product->setDescription($requestData['description'] ?? ''); // устанавливаем описание продукта из данных запроса (или пустую строку, если описание не было передано)
            $this->entityManager->persist($product); // помечаем объект продукта для сохранения в базе данных
            $this->entityManager->flush(); // сохраняем изменения в базе данных (выполняем SQL-запрос для вставки нового продукта)
            return $this->redirect('/product/' . $product->getId()); // перенаправляем пользователя на страницу со списком продуктов после успешного создания нового продукта   
        }
        return $this->render('product/create'); // отображаем форму для создания нового продукта
    }
}
