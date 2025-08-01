<?php

namespace App\Twig\Components;

use App\Repository\InvoiceRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class InvoiceCount
{
    use DefaultActionTrait;
    public function __construct(private InvoiceRepository $invoiceRepository)
    {
    }
    public function getCount(): int
    {
        return $this->invoiceRepository->count([]);
    }
}
