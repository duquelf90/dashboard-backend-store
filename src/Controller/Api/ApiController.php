<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Invoice\CreateInvoice;
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
        $baseUrl = $request->getSchemeAndHttpHost();
        $products = [];

        foreach ($pagination->getItems() as $product) {
            $productData = $serializer->normalize($product, null, ['groups' => ['product_list']]);

            $images = $product->getImages()->toArray();

            if (!empty($images)) {
                $productData['images'] = array_map(function ($image) use ($baseUrl) {
                    return $baseUrl . '/uploads/product_images/' . $image; // Ajusta la ruta a tu estructura
                }, $images);
                $productData['thumbnail'] = $baseUrl . '/uploads/product_images/' . $images[0]; // Primer elemento de images
            } else {
                $productData['images'] = []; // Manejar el caso en que no haya imágenes
                $productData['thumbnail'] = null; // O un valor por defecto
            }

            $products[] = $productData;
        }

        // $data = $serializer->serialize($pagination->getItems(), 'json', ['groups' => ['product_list']]);

        return $this->json([
            'products' => $products,
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
        CreateInvoice $createInvoice,
        ProductRepository $productRepository,
        NotificationService $notificationService,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        // customer data
        $customer = $data['customer']['fullname'];
        $email = $data['customer']['email'];
        $customer_phone = $data['customer']['phone'];
        $customer_address = $data['customer']['address'];


        // recipient
        $recipient = $data['recipient']['fullname'];
        $recipient_phone = $data['recipient']['phone'];
        $recipient_address = $data['recipient']['address'];
        $recipient_province = $data['recipient']['province'];
        $notes = $data['recipient']['notes'];


        // pasar el id del negocio o mejor jalarlo del producto pero mejor que venga desde el store
        $order = new Order(
            $customer,
            $email,
            $customer_phone,
            $customer_address,
            'pendiente',
            $recipient,
            $recipient_phone,
            $recipient_address,
            $recipient_province,
            $notes
        );
        $total = 0;

        foreach ($data['products'] as $prodData) {
            $product = $productRepository->findOneBy(['id' => $prodData['id']]);
            $detalle = new OrderDetail();
            $detalle->setOrderId($order);
            $detalle->setProduct($product);
            $detalle->setQuantity($prodData['quantity']);
            $detalle->setUnitPrice($prodData['price']);
            $subtotal = $prodData['price'] * $prodData['quantity'];
            $detalle->setSubtotal($subtotal);

            $total += $subtotal;
            $entityManager->persist($detalle);
        }
        $order->setBusiness($product->getUser());
        $order->setTotal($total);
        $entityManager->persist($order);
        $entityManager->flush();
        $createInvoice($order);
        $notificationService($order, $product->getUser());


        return $this->json(['mensaje' => 'Orden creada exitosamente', 'orden' => $order], 201, [], ['groups' => ['order:full']]);
    }
}
