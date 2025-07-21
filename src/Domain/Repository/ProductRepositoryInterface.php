<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Category;
use App\Domain\ValueObject\Price;

interface ProductRepositoryInterface
{
    public function findAllByCategory(Category $category, int $page): iterable;
    public function findAllByPriceLessThan(Price $price, int $page): iterable;
    public function all(int $page): iterable;
}
