<?php

namespace App\Twig\Components;

use App\Repository\CategoryRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class CategorySearch
{
    use DefaultActionTrait;
    #[LiveProp(writable: true)]
    public string $query = '';
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->search($this->query);
    }
}
