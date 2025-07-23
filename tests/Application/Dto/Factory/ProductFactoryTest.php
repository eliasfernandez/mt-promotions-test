<?php

namespace App\Tests\Application\Dto\Factory;

use App\Application\Dto\Factory\ProductFactory;
use App\Domain\Entity\Category;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductDiscount;
use App\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class ProductFactoryTest extends TestCase
{
    public function testBasicCreate(): void
    {
        $category = new Category();
        $category->setIdentifier('boots');

        $product = new Product();
        $product->setSku('sku-1234')
            ->setName('name-1234')
            ->setCategory($category)
            ->setPrice(new Price(78));

        $factory = new ProductFactory();

        $dto = $factory->fromEntity($product);

        $this->assertEquals('sku-1234', $dto->getSku());
        $this->assertEquals('name-1234', $dto->getName());
        $this->assertEquals($category->getIdentifier(), $dto->getCategory());

        $this->assertEquals(78, $dto->getPrice()->getOriginal());
        $this->assertEquals(78, $dto->getPrice()->getFinal());
        $this->assertNull($dto->getPrice()->getDiscountPercentage());
        $this->assertEquals('EUR', $dto->getPrice()->getCurrency());
    }

    public function testDiscountedCreate(): void
    {
        $category = new Category();
        $category->setIdentifier('boots');

        $discount = new ProductDiscount();
        $discount->setPercentage('2.5');

        $product = new Product();
        $product->setSku('sku-1234')
            ->setName('name-1234')
            ->setCategory($category)
            ->setPrice(new Price(122_00))
            ->addDiscount($discount);

        $factory = new ProductFactory();

        $dto = $factory->fromEntity($product);

        $this->assertEquals('sku-1234', $dto->getSku());
        $this->assertEquals('name-1234', $dto->getName());
        $this->assertEquals($category->getIdentifier(), $dto->getCategory());

        $this->assertEquals(122_00, $dto->getPrice()->getOriginal());
        $this->assertEquals(119_56, $dto->getPrice()->getFinal());
        $this->assertEquals('2.5%', $dto->getPrice()->getDiscountPercentage());
        $this->assertEquals('EUR', $dto->getPrice()->getCurrency());
    }
}
