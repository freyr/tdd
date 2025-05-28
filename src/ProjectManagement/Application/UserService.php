<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Application;

use Freyr\TDD\ProjectManagement\Domain\UserRepository;
use Freyr\TDD\ProjectManagement\Domain\ProjectManager;
use Freyr\TDD\ProjectManagement\Domain\User;
use Freyr\TDD\ProjectManagement\Domain\Admin;
use Freyr\TDD\ProjectManagement\Domain\ProjectId;

class UserService
{
    private UserRepository $userRepository;
    private ProjectManager $projectManager;

    public function __construct(UserRepository $userRepository, ProjectManager $projectManager)
    {
        $this->userRepository = $userRepository;
        $this->projectManager = $projectManager;
    }

    public function assignUserToProject(string $userEmail, Admin $admin, ProjectId $projectId): bool
    {
        $user = $this->userRepository->findByEmail($userEmail);
        if (!$user) {
            return false;
        }
        return $this->projectManager->assignUserToProject($user, $admin, $projectId);
    }
}