<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\InvoiceService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]

final class ApiController extends AbstractController
{

    #[Route(path: '/products', name: 'api_products', methods: ['GET'])]
    public function product(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator, SerializerInterface $serializer): JsonResponse
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

    #[Route(path: '/categories', name: 'api_categories', methods: ['GET'])]
    public function categories(CategoryRepository $categoryRepository): JsonResponse
    {
        $data = $categoryRepository->findAll();
        return $this->json(['items' => $data], 201, [], ['groups' => ['category:read']]);
    }

    #[Route(path: '/create/order', name: 'api_order_create', methods: ['POST'])]

    public function createOrder(
        Request $request,
        EntityManagerInterface $entityManager,
        InvoiceService $invoiceService,
        ProductRepository $productRepository,
        NotificationService $notificationService,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        // Datos ficticios para el cliente
        $customer = $data['cliente']['nombre'] ?? 'John Doe';
        $email = $data['cliente']['correo'] ?? 'john.doe@example.com';
        $customer_phone = $data['cliente']['telefono'] ?? '123456789';
        $customer_address = $data['cliente']['direccion'] ?? '123 Main St';
        
        // pasar el id del negocio o mejor jalarlo del producto pero mejor que venga desde el store
        $order = new Order($customer, $email, $customer_phone, $customer_address, 'pendiente');
        $total = 0;

        foreach ($data['productos'] as $prodData) {
            $product = $productRepository->findOneBy(['id' => $prodData['id']]);
            $detalle = new OrderDetail();
            $detalle->setOrderId($order);
            $detalle->setProduct($product);
            $detalle->setQuantity($prodData['cantidad']);
            $detalle->setUnitPrice($prodData['precio']);
            $subtotal = $prodData['precio'] * $prodData['cantidad'];
            $detalle->setSubtotal($subtotal);

            $total += $subtotal;
            $entityManager->persist($detalle);
        }
        $order->setBusiness($product->getUser());
        $order->setTotal($total);
        $entityManager->persist($order);
        $entityManager->flush();
        $invoiceService($order);
        $notificationService($order, $product->getUser());


        return $this->json(['mensaje' => 'Orden creada exitosamente', 'orden' => $order], 201, [], ['groups' => ['order:full']]);
    }
}
