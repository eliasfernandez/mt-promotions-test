<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Category;
use App\Domain\Entity\CategoryDiscount;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductDiscount;
use App\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testSkuGetterAndSetter(): void
    {
        $product = new Product();
        $product->setSku('SKU123');

        $this->assertSame('SKU123', $product->getSku());
    }

    public function testNameGetterAndSetter(): void
    {
        $product = new Product();
        $product->setName('Boots');

        $this->assertSame('Boots', $product->getName());
    }

    public function testPriceGetterAndSetter(): void
    {
        $price = new Price(1000, 'USD');
        $product = new Product();
        $product->setPrice($price);

        $this->assertSame($price, $product->getPrice());
    }

    public function testCategoryAssociation(): void
    {
        $category = new Category();
        $product = new Product();

        $product->setCategory($category);

        $this->assertSame($category, $product->getCategory());
    }

    public function testBasicProductDiscount(): void
    {
        $category = new Category();
        $product = new Product();
        $product->setCategory($category);

        $product->setPrice(new Price(19_99, 'USD'));
        $product->addDiscount($this->createProductDiscount('20'));

        $this->assertTrue($product->getFinalPrice()->equalsTo(new Price(16_00, 'USD')));
        $this->assertSame('20', $product->getDiscountPercentage());
    }

    public function testCollidingDiscountAlwaysGetMax(): void
    {
        $category = new Category();
        $category->setIdentifier('sneakers');
        $category->addDiscount($this->createCategoryDiscount('19.99'));

        $product = new Product();
        $product->setPrice(new Price(19_99, 'EUR'));
        $product->addDiscount($this->createProductDiscount('20'));
        $product->addDiscount($this->createProductDiscount('19'));
        $product->addDiscount($this->createProductDiscount('18'));
        $product->addDiscount($this->createProductDiscount('1.5'));
        $product->setCategory($category);

        $this->assertTrue($product->getFinalPrice()->equalsTo(new Price(16_00)));
        $this->assertSame('20', $product->getDiscountPercentage());
    }

    public function testCollidingDiscountAlwaysGetMaxCategoryVersion(): void
    {
        $category = new Category();
        $category->setIdentifier('sneakers');
        $category->addDiscount($this->createCategoryDiscount('30.00'));

        $product = new Product();
        $product->setPrice(new Price(19_99, 'EUR'));
        $product->addDiscount($this->createProductDiscount('20'));
        $product->addDiscount($this->createProductDiscount('19'));
        $product->addDiscount($this->createProductDiscount('18'));
        $product->addDiscount($this->createProductDiscount('1.5'));
        $product->setCategory($category);

        $this->assertTrue($product->getFinalPrice()->equalsTo(new Price(14_00)));
        $this->assertSame('30.00', $product->getDiscountPercentage());
    }

    public function testNoDiscounts(): void
    {
        $category = new Category();
        $category->setIdentifier('sneakers');

        $product = new Product();
        $product->setCategory($category);
        $product->setPrice(new Price(19_99, 'EUR'));

        $this->assertTrue($product->getFinalPrice()->equalsTo(new Price(19_99)));
        $this->assertSame(null, $product->getDiscountPercentage());
    }

    private function createProductDiscount(string $percentage): ProductDiscount
    {
        $discount = new ProductDiscount();
        $discount->setPercentage($percentage);

        return $discount;
    }

    private function createCategoryDiscount(string $percentage): CategoryDiscount
    {
        $discount = new CategoryDiscount();
        $discount->setPercentage($percentage);

        return $discount;
    }
}
