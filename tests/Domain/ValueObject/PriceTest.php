<?php

namespace App\Tests\Domain\ValueObject;

use App\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testGetters(): void
    {
        $price = new Price(100_00);
        $this->assertEquals(100_00, $price->getAmount());
        $this->assertEquals('EUR', $price->getCurrency());

        $price2 = new Price(120_00, 'DOL');
        $this->assertEquals(120_00, $price2->getAmount());
        $this->assertEquals('DOL', $price2->getCurrency());
    }

    public function testEqualsTo(): void
    {
        $price = new Price(100_00);

        $this->assertTrue($price->equalsTo(new Price(100_00)));
        $this->assertFalse($price->equalsTo(new Price(100_00, 'DOL')));
        $this->assertFalse($price->equalsTo(new Price(120_00)));
    }

    public function testZeroAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Price(0);
    }

    public function testNegativeAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Price(-100);
    }
}
