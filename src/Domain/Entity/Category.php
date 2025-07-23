<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Category
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\Column]
    private string $identifier;

    #[ORM\Column(length: 255)]
    private string $name;


    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private Collection $products;


    /**
     * @var Collection<int, CategoryDiscount>
     */
    #[ORM\OneToMany(targetEntity: CategoryDiscount::class, mappedBy: 'category')]
    private Collection $discounts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->discounts = new ArrayCollection();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): Category
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): Category
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoryDiscount>
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    public function addDiscount(CategoryDiscount $discount): Category
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts->add($discount);
            $discount->setCategory($this);
        }

        return $this;
    }
}
