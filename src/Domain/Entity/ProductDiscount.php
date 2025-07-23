<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Traits\DiscountTrait;
use App\Domain\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ProductDiscount implements DiscountInterface
{
    use TimestampTrait;
    use DiscountTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'discounts')]
    #[ORM\JoinColumn(name: 'sku', referencedColumnName: 'sku', nullable: false)]
    private Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
