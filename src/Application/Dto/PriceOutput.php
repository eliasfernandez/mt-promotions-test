<?php

namespace App\Application\Dto;

class PriceOutput
{
    public function __construct(
        private int $original,
        private int $final,
        private ?string $discountPercentage,
        private string $currency,
    ) {
    }

    public function getOriginal(): int
    {
        return $this->original;
    }

    public function getFinal(): int
    {
        return $this->final;
    }

    public function getDiscountPercentage(): ?string
    {
        return $this->discountPercentage;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
