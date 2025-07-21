<?php

namespace App\Application\Dto\Factory;

use App\Application\Dto\PriceOutput;
use App\Application\Dto\ProductOutput;
use App\Domain\Entity\Product;

class ProductFactory
{
    public function fromEntity(Product $object): ProductOutput
    {
        return new ProductOutput(
            $object->getSku(),
            $object->getName(),
            $object->getCategory()->getIdentifier(),
            new PriceOutput(
                $object->getPrice()->getAmount(),
                $object->getFinalPrice()->getAmount(),
                $object->getDiscountPercentage(),
                $object->getPrice()->getCurrency(),
            ),
        );
    }
}
