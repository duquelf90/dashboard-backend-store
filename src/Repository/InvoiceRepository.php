<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }
    public function countByUser(User $user): int
    {
        return 0;
        // return $this->count(['user' => $user]);
    }
    public function findInvoicesByBusinessId($businessId)
    {
        return $this->createQueryBuilder('i')
            ->join('i.orderId', 'o')
            ->where('o.business = :business')
            ->setParameter('business', $businessId)
            ->getQuery()
            ->getResult();
    }
}
