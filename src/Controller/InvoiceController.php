<?php

namespace App\Controller;

use App\Entity\BackOrder;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $invoice = $form->get('invoice')->getData();
            $customer = $form->get('customer')->getData();
            $email = $form->get('email')->getData();
            $phone = $form->get('phone')->getData();
            $address = $form->get('address')->getData();
            $description = $form->get('description')->getData();

            $order = new Order($customer, $email, $phone, $address, 'pendiente');
            $total = 0;

            // Obtener el JSON de productos
            $productsJson = $request->request->get('products');
            $products = json_decode($productsJson, true);
            // Crear un array de respuesta
            $responseData = [
                'invoice' => [
                    'invoice' => $invoice,  // Asegúrate de tener un método getter
                    'customer' => $customer,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'description' => $description,
                ],
                'products' => $products,
                'status' => 'success',
            ];

            // Procesar la información como desees (guardar en base de datos, etc.)
            foreach ($products as $product) {
                // Aquí puedes crear una entidad de producto y persistirla
                $newProduct = new BackOrder();
                $newProduct->setProduct($product['name']);
                $newProduct->setUnitPrice($product['price']);
                $newProduct->setQuantity($product['quantity']);

                $subtotal = $product['price'] * $product['quantity'];
                $newProduct->setSubtotal($product['subtotal']);
                $total += $subtotal;

                $entityManager->persist($newProduct);
            }

            // $order->setBusiness($product->getUser());
            $order->setTotal(total: $total);

            $entityManager->persist($order);
            $entityManager->flush();
            
            return $this->json($responseData);

            // return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
