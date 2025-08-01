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

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var array <string, Category>
     */
    private array $categories = [];

    /**
     * @var array <string, Product>
     */
    private array $products = [];

    public function load(ObjectManager $manager): void
    {
        $this->initializeCategories();

        foreach ($this->categories as $category) {
            $manager->persist($category);
        }

        $json = json_decode(
            file_get_contents(__DIR__ . '/Resources/mt-test.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($json['products'] as $item) {
            $this->products[$item['sku']] = $this->createProduct($item);
            $manager->persist($this->products[$item['sku']]);
        }

        $bootsDiscount = new CategoryDiscount();
        $bootsDiscount->setPercentage('30');
        $this->categories['boots']->addDiscount($bootsDiscount);

        $itemDiscount = new ProductDiscount();
        $itemDiscount->setPercentage('15');
        $this->products['000003']->addDiscount($itemDiscount);

        $manager->persist($bootsDiscount);
        $manager->persist($itemDiscount);
        $manager->flush();
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

    /**
     * @return Product
     */
    public function createProduct(array $item): Product
    {
        $product = new Product();
        $product->setName($item['name'])
            ->setPrice(new Price($item['price']))
            ->setSku($item['sku'])
            ->setCategory($this->categories[$item['category']]);
        return $product;
    }

    public static function getGroups(): array
    {
        return ['basic'];
    }
}
