<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class OrderCount
{
    use DefaultActionTrait;
    private ?User $user;
    public function __construct(private OrderRepository $orderRepository, Security $security)
    {
        $this->user = $security->getUser();
    }

    public function getCount(): int
    {
        return $this->orderRepository->countByUser($this->user);
    }
    

}
