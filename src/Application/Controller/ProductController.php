<?php

namespace App\Application\Controller;

use App\Application\Dto\Factory\ProductFactory;
use App\Domain\Entity\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    /**
     * @param ProductRepository<Product> $productRepository
     */
    #[Route('/products', name: 'app_product')]
    public function list(
        ProductRepository $productRepository,
        ProductFactory $productFactory,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT, options: ['min_range' => 1], validationFailedStatusCode: 404)]
        int $page = 1,
        #[MapQueryParameter]
        ?string $category = null,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT, options: ['min_range' => 1], validationFailedStatusCode: 404)]
        ?int $priceLessThan = null,
    ): JsonResponse {
        $products = $productRepository->filter($page, $category, $priceLessThan);

        if (null === $products) {
            return $this->json(['error' => 'No products found',], 404);
        }

        return $this->json([
            'count' => count($products),
            'page' => $page,
            'products' => array_map(
                fn (Product $product) => $productFactory->fromEntity($product),
                iterator_to_array($products)
            ),
        ]);
    }
}
