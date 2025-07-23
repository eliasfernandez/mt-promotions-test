<?php

namespace App\Tests\Application\Controller;

use App\Application\Controller\ProductController;
use App\Application\Dto\Factory\ProductFactory;
use App\Domain\Entity\Category;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\Price;
use App\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductControllerTest extends KernelTestCase
{

    private ProductController $controller;
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->controller = $container->get(ProductController::class); // ✅ use DI
    }

    public function testList(): void
    {
        self::bootKernel();

        $repository = $this->createMock(ProductRepository::class);
        $factory = new ProductFactory();

        $categoryA = new Category();
        $categoryA->setIdentifier('a');
        $categoryB = new Category();
        $categoryB->setIdentifier('b');

        $repository->expects($this->once())
            ->method('filter')
            ->with(2)
            ->willReturn(new \ArrayIterator([
                $this->createProduct('product 1', 10_00, '001', $categoryA),
                $this->createProduct('product 2', 99_00, '002', $categoryB),
                $this->createProduct('product 3', 1_00, '003', $categoryA),
                $this->createProduct('product 4', 12_00, '004', $categoryB),
            ]));

        $response = $this->controller->list($repository, $factory, 2);

        $this->assertEquals(
            '{"count":4,"page":2,"products":[{"sku":"001","name":"product 1","category":"a","price":{"original":1000,"final":1000,"discount_percentage":null,"currency":"EUR"}},{"sku":"002","name":"product 2","category":"b","price":{"original":9900,"final":9900,"discount_percentage":null,"currency":"EUR"}},{"sku":"003","name":"product 3","category":"a","price":{"original":100,"final":100,"discount_percentage":null,"currency":"EUR"}},{"sku":"004","name":"product 4","category":"b","price":{"original":1200,"final":1200,"discount_percentage":null,"currency":"EUR"}}]}',
            $response->getContent()
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testListNotFound(): void
    {
        self::bootKernel();

        $repository = $this->createMock(ProductRepository::class);
        $factory = new ProductFactory();

        $repository->expects($this->once())
            ->method('filter')
            ->with(100, 'c', 10_00)
            ->willReturn(null);

        $response = $this->controller->list($repository, $factory, 100, 'c', 10_00);

        $this->assertEquals(
            '{"error":"No products found"}',
            $response->getContent()
        );

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function createProduct(string $name, int $price, string $sku, Category $category): Product
    {
        $product = new Product();
        $product->setName($name)
            ->setPrice(new Price($price))
            ->setSku($sku)
            ->setCategory($category);

        return $product;
    }
}
