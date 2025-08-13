<?php

namespace App\Twig\Components;

use App\Repository\NotificationRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class NotificationBell
{
    use DefaultActionTrait;
    public function __construct(private NotificationRepository $notificationRepository) {}

    #[LiveProp(useSerializerForHydration: true)]
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->loadUnreadCount();
    }

    public function loadUnreadCount(): void
    {
        $this->unreadCount = $this->notificationRepository->countUnread();
    }
}
