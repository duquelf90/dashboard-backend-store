<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class InvoiceCount
{
    use DefaultActionTrait;
    private ?User $user;
    public function __construct(private InvoiceRepository $invoiceRepository, Security $security)
    {
        $this->user = $security->getUser();
    }

    public function getCount(): int
    {
        return $this->invoiceRepository->count([]);
    }

    public function getCountByUser(): int
    {
        return $this->invoiceRepository->countByUser($this->user);
    }
}
