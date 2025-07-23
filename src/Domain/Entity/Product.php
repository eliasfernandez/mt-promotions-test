<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Traits\TimestampTrait;
use App\Domain\ValueObject\Price;
use App\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Index(name: "price_idx", columns: ["price_amount"])]
#[ORM\Index(name: "category_idx", columns: ["category"])]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\Column]
    private string $sku;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Embedded(class: Price::class)]
    private Price $price;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category', referencedColumnName: 'identifier', nullable: false)]
    private Category $category;

    /**
     * @var Collection<ProductDiscount>
     */
    #[ORM\OneToMany(targetEntity: ProductDiscount::class, mappedBy: 'product')]
    private Collection $discounts;

    public function __construct()
    {
        $this->discounts = new ArrayCollection();
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<ProductDiscount>
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    public function addDiscount(ProductDiscount $discount): static
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts->add($discount);
            $discount->setProduct($this);
        }

        return $this;
    }

    public function getFinalPrice(): Price
    {
        return new Price(
            $this->price->getAmount() - $this->getDiscountAmount(),
            $this->price->getCurrency(),
        );
    }

    public function getDiscountAmount(): ?int
    {
        $discountPercentage = $this->getDiscountPercentage();

        if ($discountPercentage === null) {
            return null;
        }

        return (int) bcmul(
            $this->price->getAmount(),
            bcdiv($discountPercentage, 100, 2),
            2
        );
    }

    public function getDiscountPercentage(): ?string
    {
        $discounts = array_merge(
            $this->discounts->toArray(),
            $this->category->getDiscounts()->toArray()
        );

        if (empty($discounts)) {
            return null;
        }

        return array_reduce(
            $discounts,
            fn ($carry, DiscountInterface $item) => bccomp($item->getPercentage(), $carry) === 1 ? $item->getPercentage() : $carry,
            '0'
        );
    }
}
