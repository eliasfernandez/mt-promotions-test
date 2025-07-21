<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\Category;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\Price;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{

    public function findAllByCategory(Category $category, int $page = 1): iterable
    {
        // TODO: Implement findAllByCategory() method.
    }

    public function findAllByPriceLessThan(Price $price, int $page = 1): iterable
    {
        // TODO: Implement findAllByPriceLessThan() method.
    }

    public function all(int $page = 1): iterable
    {

    }
}
