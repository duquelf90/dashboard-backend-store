<?php

namespace App\Service\Invoice;

use App\Entity\Invoice;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;

final class PaymentInvoice
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerService $mailerService
    ) {
    }
    public function __invoke(Invoice $invoice): void
    {
        $invoice->setStatus('pagado');
        $this->entityManager->flush();

        $order = $invoice->getOrder();
        $customerEmail = $order->getCustomerEmail();

        $subject = 'Factura Pagada';
        $body = sprintf(
            '<p>Estimado %s,</p><p>Su factura #%d ha sido pagada exitosamente.</p>',
            $order->getCustomer(),
            $invoice->getId()
        );

        $this->mailerService->sendEmail($customerEmail, $subject, $body);


    }
}


