<?php

namespace App\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Price
{
    public const DEFAULT_CURRENCY = 'EUR';

    public function __construct(
        #[ORM\Column(type: "integer")]
        private int $amount,
        private string $currency = self::DEFAULT_CURRENCY,
    ) {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero.');
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function equalsTo(Price $price): bool
    {
        return $this->currency === $price->getCurrency() && $this->amount === $price->getAmount();
    }
}
