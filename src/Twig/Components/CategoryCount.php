<?php

namespace App\Twig\Components;

use App\Repository\CategoryRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class CategoryCount
{
    use DefaultActionTrait;
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getCount(): int
    {
        return $this->categoryRepository->count([]);
    }
}
