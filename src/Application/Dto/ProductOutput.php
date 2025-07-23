<?php

namespace App\Application\Dto;

class ProductOutput implements \JsonSerializable
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

    /**
     * @return array{sku: string, name: string, category: string, price: array{original: int, final: int, discount_percentage: string, currency: string}}
     */
    public function jsonSerialize(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->getPrice()->jsonSerialize(),
        ];
    }
}
