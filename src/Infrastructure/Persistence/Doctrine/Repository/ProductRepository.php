<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use Countable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use IteratorAggregate;

/**
 * @template Product of object
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public const RESULT_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function filter(int $page, ?string $category, ?int $priceLessThan): IteratorAggregate|Countable|null
    {
        $builder = $this->createQueryBuilder('p');
        if (null !== $category && '' !== $category) {
            $builder->where('p.category = :category')
                ->setParameter('category', $category);
        }
        if (null !== $priceLessThan) {
            $builder->andWhere('p.price.amount <= :priceLessThan')
                ->setParameter('priceLessThan', $priceLessThan);
        }
        $query = $builder->getQuery();

        return $this->paginate($query, $page);
    }

    private function paginate(Query $query, int $page): IteratorAggregate|Countable|null
    {
        $firstResult = ($page - 1) * self::RESULT_PER_PAGE;
        $query->setFirstResult($firstResult)
            ->setMaxResults(self::RESULT_PER_PAGE);
        $paginator = new Paginator($query);

        if (count($paginator) <= $firstResult) {
            return null;
        }

        return $paginator;
    }
}
