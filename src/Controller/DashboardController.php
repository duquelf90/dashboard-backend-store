<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    
}
