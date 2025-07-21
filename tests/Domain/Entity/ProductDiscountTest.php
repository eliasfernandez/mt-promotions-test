<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Product;
use App\Domain\Entity\ProductDiscount;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ProductDiscountTest extends TestCase
{
    public function testSetAndGetPercentage()
    {
        $discount = new ProductDiscount();

        $discount->setPercentage('10.5');
        $this->assertEquals('10.5', $discount->getPercentage());

        $discount->setPercentage('0.25');
        $this->assertEquals('0.25', $discount->getPercentage());

        $discount->setPercentage('99');
        $this->assertEquals('99', $discount->getPercentage());
    }

    #[DataProvider('provideInvalidPercentages')]
    public function testSetPercentageThrowsExceptionOnInvalidInput($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ProductDiscount())->setPercentage($value);
    }

    public static function provideInvalidPercentages(): array
    {
        return [
            ['100'],
            ['0'],
            ['0,25'],
            ['abc'],
            ['-10'],
            [''],
            [' '],
        ];
    }

    public function testSetAndGetProduct()
    {
        $product = $this->createMock(Product::class);
        $discount = new ProductDiscount();

        $discount->setProduct($product);
        $this->assertSame($product, $discount->getProduct());
    }
}
