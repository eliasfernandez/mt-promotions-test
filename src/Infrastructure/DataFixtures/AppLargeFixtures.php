<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Category;
use App\Domain\Entity\CategoryDiscount;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductDiscount;
use App\Domain\ValueObject\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppLargeFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var array <string, Category>
     */
    private array $categories = [];
    private array $names = [
        'BV Lean leather ankle ',
        'Ashlington leather ankle ',
        'Naima embellished suede ',
        'Nathane leather '
    ];

    public function load(ObjectManager $manager): void
    {
        $this->initializeCategories();

        foreach ($this->categories as $category) {
            $manager->persist($category);
        }

        $bootsDiscount = new CategoryDiscount();
        $bootsDiscount->setPercentage('30');
        $this->categories['boots']->addDiscount($bootsDiscount);
        $manager->persist($bootsDiscount);

        for ($i = 0; $i < 20000; $i++) {
            $product = $this->createProduct($i);
            $manager->persist($product);

            if ($i % 100 === 0) {
                $manager->flush();
            }
        }

        $manager->flush();
        $manager->clear();
    }

    private function initializeCategories(): void
    {
        $this->categories = [
            'boots' => $this->createCategory('boots'),
            'sandals' => $this->createCategory('sandals'),
            'sneakers' => $this->createCategory('sneakers'),
        ];
    }

    public function createCategory(string $string): Category
    {
        $category = new Category();
        $category->setName(ucfirst($string))
            ->setIdentifier($string);

        return $category;
    }

    public function createProduct(int $i): Product
    {
        $product = new Product();

        $category = $this->categories[array_rand($this->categories)];

        $product->setName($this->names[array_rand($this->names)] . $category->getName())
            ->setPrice(new Price(rand(1_00, 1000_00)))
            ->setSku(sprintf('sku-%05d', $i))
            ->setCategory($category);

        return $product;
    }

    public static function getGroups(): array
    {
        return ['large'];
    }
}
