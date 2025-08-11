<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

final class InvoiceService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }
    public function __invoke(Order $order): Invoice
    {

        $factura = new Invoice();
        $factura->setOrderId($order);
        $factura->setTotalAmount($order->getTotal());
        $factura->setStatus('pendiente');
        $this->entityManager->persist($factura);
        $this->entityManager->flush();

        return $factura;
    }
}


