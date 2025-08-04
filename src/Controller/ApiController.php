<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]

final class ApiController extends AbstractController
{
    #[Route('/products', name: 'api_products', methods: ['GET'])] 
    public function product(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator,SerializerInterface $serializer): JsonResponse
    {
        $pagination = $paginator->paginate(
            $productRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        $data = $serializer->serialize($pagination->getItems(), 'json', ['groups' => ['product_list']]);
        
        return $this->json([
            'items' => json_decode($data),
            'total' => $pagination->getTotalItemCount(),
            'page' => $pagination->getCurrentPageNumber(),
            'limit' => $pagination->getItemNumberPerPage(),
        ]);
    }
}
