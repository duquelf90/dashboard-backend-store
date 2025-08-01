<?php

namespace App\Twig\Components;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProductCount
{
    use DefaultActionTrait;
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getCount(): int
    {
        return $this->productRepository->count([]);
    }
}
