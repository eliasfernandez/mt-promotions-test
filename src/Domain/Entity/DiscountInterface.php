<?php

namespace App\Domain\Entity;

interface DiscountInterface
{
    public function getPercentage(): string;
}
