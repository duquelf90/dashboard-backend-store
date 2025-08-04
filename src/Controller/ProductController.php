<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, #[CurrentUser] User $user): Response
    {
        $products = $productRepository->findBy(['user' => $user]);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user, ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $uploadedFiles */
            $uploadedFiles = $request->files->get('images');

            if ($uploadedFiles) {
                foreach ($uploadedFiles as $file) {
                    if ($file instanceof UploadedFile) {
                        $filename = uniqid() . '.' . $file->guessExtension();
                        $file->move($this->getParameter('product_images_directory'), $filename);
                        $image = new Image();
                        $image->setFilename($filename);
                        $product->addImage($image); // Asociar la imagen con el producto
                    }
                }
            }
            $product->setUser($user);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $uploadedFiles */
            $uploadedFiles = $request->files->get('images');

            if ($uploadedFiles) {
                foreach ($uploadedFiles as $file) {
                    if ($file instanceof UploadedFile) {
                        $filename = uniqid() . '.' . $file->guessExtension();
                        $file->move($this->getParameter('product_images_directory'), $filename);
                        $image = new Image();
                        $image->setFilename($filename);
                        $product->addImage($image); // Asociar la imagen con el producto
                    }
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {

            foreach ($product->getImages() as $image) {
                $filePath = $this->getParameter('product_images_directory') . '/' . $image->getFilename();
                if (file_exists($filePath)) {
                    unlink($filePath); // Eliminar el archivo del sistema
                }
            }
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/remove-image/{imageId}', name: 'product_remove_image', methods: ['DELETE'])]
    public function removeImage(int $id, int $imageId, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        $image = $product->getImages()->filter(fn($img) => $img->getId() === $imageId)->first();

        if (!$image) {
            return $this->json(['message' => 'Image not found'], Response::HTTP_NOT_FOUND);
        }
        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/product_images/' . $image->getFilename();
        // Eliminar el archivo del sistema de archivos
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return $this->json(['message' => 'Failed to delete image file'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        $product->removeImage($image);
        $entityManager->remove($image);
        $entityManager->flush();

        return $this->json(['message' => 'Image removed successfully']);
    }


}
