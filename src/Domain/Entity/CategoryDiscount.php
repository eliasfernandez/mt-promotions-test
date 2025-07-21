<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class CategoryDiscount implements DiscountInterface
{
    use TimestampTrait;
    use DiscountTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'discounts')]
    #[ORM\JoinColumn(name: 'category', referencedColumnName: 'identifier', nullable: false)]
    private Category $category;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: false)]
    private string $percentage;

    public function getId(): ?int
    {
        return $this->id;
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
}
