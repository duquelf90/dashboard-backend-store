<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProductCount
{
    use DefaultActionTrait;
    private ?User $user;

    public function __construct(private ProductRepository $productRepository, Security $security)
    {
        $this->user = $security->getUser();
    }

    public function getCount(): int
    {
        return $this->productRepository->count([]);
    }

    public function getCountByUser(): int
    {
        return $this->productRepository->countByUser($this->user);
    }
}
