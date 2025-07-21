<?php

namespace App\Application\Dto;

class ProductOutput
{
    public function __construct(
        private string $sku,
        private string $name,
        private string $category,
        private PriceOutput $price
    ) {
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPrice(): PriceOutput
    {
        return $this->price;
    }
}
