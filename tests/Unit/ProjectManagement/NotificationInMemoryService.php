<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit\ProjectManagement;

use Freyr\TDD\Notifications\Notification;
use Freyr\TDD\Notifications\NotificationService;

class NotificationInMemoryService implements NotificationService
{
    public array $notifications;

    public function __construct()
    {
        $this->notifications = [];
    }

    public function sendNotification(Notification $notification): void
    {
        $this->notifications[] = json_encode($notification);
    }
}