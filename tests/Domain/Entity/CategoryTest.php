<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testIdentifierGetterAndSetter()
    {
        $category = new Category();
        $category->setIdentifier('cat123');

        $this->assertSame('cat123', $category->getIdentifier());
    }

    public function testNameGetterAndSetter()
    {
        $category = new Category();
        $category->setName('Boots');

        $this->assertSame('Boots', $category->getName());
    }

    public function testInitialProductsIsEmpty()
    {
        $category = new Category();
        $this->assertCount(0, $category->getProducts());
    }
}
