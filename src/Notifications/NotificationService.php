<?php

declare(strict_types=1);

namespace Freyr\TDD\Notifications;

interface NotificationService
{
    public function sendNotification(Notification $notification): void;
}