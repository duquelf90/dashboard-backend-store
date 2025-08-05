<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\NotificationRepository;

final class NotificationService
{
    public function __construct(private NotificationRepository $notificationRepository)
    {
    }
    public function __invoke(Order $order, User $user): void
    {
        $notification = new Notification($user, "Nueva orden recibida: " . $order->getId());
        $this->notificationRepository->save($notification);

    }
}


