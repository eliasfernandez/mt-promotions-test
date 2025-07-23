<?php

namespace App\Domain\Repository;

interface ProductRepositoryInterface
{
    public function filter(int $page, ?string $category, ?int $priceLessThan): \IteratorAggregate|\Countable|null;
}
