<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Application;

use Freyr\TDD\Notifications\Notification;
use Freyr\TDD\Notifications\NotificationService;
use Freyr\TDD\ProjectManagement\Domain\UserRepository;
use Freyr\TDD\ProjectManagement\Domain\ProjectManager;
use Freyr\TDD\ProjectManagement\Domain\Admin;
use Freyr\TDD\ProjectManagement\Domain\ProjectId;

readonly class UserService
{

    public function __construct(
        private UserRepository $userRepository,
        private ProjectManager $projectManager,
        private NotificationService $notificationService,
    )
    {}

    public function assignUserToProject (string $userEmail, Admin $admin, ProjectId $projectId): bool
    {
        $user = $this->userRepository->findByEmail($userEmail);
        if (!$user) {
            return false;
        }

        $status = $this->projectManager->assignUserToProject($user, $admin, $projectId);

        if ($status) {
            $this->userRepository->persist($user);
            $this->notificationService->sendNotification(new Notification());
        }

        return $status;
    }
}