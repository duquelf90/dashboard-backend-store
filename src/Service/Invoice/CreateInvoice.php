<?php

namespace App\Service\Invoice;

use App\Entity\Invoice;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

final class CreateInvoice
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }
    public function __invoke(Order $order): Invoice
    {

        $factura = new Invoice();
        $factura->setOrder($order);
        $factura->setTotalAmount($order->getTotal());
        $factura->setStatus('pendiente');
        $this->entityManager->persist($factura);
        $this->entityManager->flush();

        return $factura;
    }
}


