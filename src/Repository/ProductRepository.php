<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function search(string $query): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%'.$query.'%');

        return $qb->getQuery()->getResult();
    }


}
