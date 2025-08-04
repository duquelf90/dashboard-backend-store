<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route('/dashboard')]
final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(OrderRepository $orderRepository, #[CurrentUser] User $user): Response
    {
        $orders = $orderRepository->findBy(['business' => $user]);
        return $this->render('dashboard/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        return $this->render('test.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/upload-images', name: 'upload_images', methods: ['POST'])]
    public function uploadImages(Request $request): JsonResponse
    {
        $uploadedFiles = $request->files->get('images');
        $uploadedData = [];

        if ($uploadedFiles) {
            foreach ($uploadedFiles as $file) {
                if ($file instanceof UploadedFile) {
                    // Aquí puedes mover el archivo a un directorio deseado si lo necesitas.
                    // $file->move('path/to/directory', $file->getClientOriginalName());

                    // Solo devolvemos el nombre original y el tamaño
                    $uploadedData[] = [
                        'filename' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ];
                }
            }
        }

        return $this->json(data: ['status' => 'success', 'files' => $uploadedData]);
    }
}
