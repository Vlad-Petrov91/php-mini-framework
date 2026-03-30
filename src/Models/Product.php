<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity] // указываем, что этот класс является сущностью Doctrine
#[ORM\Table(name: 'products')] // указываем имя таблицы в базе данных, которая соответствует этой сущности

class Product
{
    #[ORM\Id] // указываем, что это поле является первичным ключом
    #[ORM\GeneratedValue] // указываем, что значение этого поля будет автоматически генерироваться базой данных (обычно автоинкремент)
    #[ORM\Column(type: 'integer')] // указываем тип данных для этого поля
    private int $id;
    #[ORM\Column(type: 'string', length: 255)] // указываем тип данных и длину для этого поля
    private string $name;
    #[ORM\Column(type: 'string', length: 700)]
    private string $description;
    #[ORM\Column(type: 'integer')]
    private int $price;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
    }
}
