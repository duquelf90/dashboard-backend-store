<?php

namespace App\Twig\Components;

use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class UserCount
{
    use DefaultActionTrait;
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getCount(): int
    {
        return $this->userRepository->count([]);
    }
}
