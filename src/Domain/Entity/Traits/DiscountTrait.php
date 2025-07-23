<?php

namespace App\Domain\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DiscountTrait
{
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: false)]
    private string $percentage;
    public function getPercentage(): string
    {
        return $this->percentage;
    }

    public function setPercentage(string $percentage): static
    {
        if (!is_numeric($percentage) || str_contains($percentage, ',')) {
            throw new \InvalidArgumentException("Percentage must be a numeric string using '.' as decimal separator.");
        }

        $value = (float) $percentage;

        if ($value <= 0 || $value >= 100) {
            throw new \InvalidArgumentException("Percentage must be greater than 0 and less than 100.");
        }
        $this->percentage = $percentage;

        return $this;
    }
}
