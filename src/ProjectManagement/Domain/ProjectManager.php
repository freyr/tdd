<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class ProjectManager
{
    public function assignUserToProject(User $user, Admin $admin, ProjectId $projectId): ?User
    {
        if (!$admin->canAssignToProjects() || !$this->isUserAllowedToHaveAnotherProject($user)) {
            return null;
        }

        $user->addProject($projectId);

        return $user;
    }

    private function isUserAllowedToHaveAnotherProject(User $user): bool
    {
        if (!$user->isActive()) {
            return false;
        }

        if ($this->userHasEnoughProjectsAlready($user)) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    private function userHasEnoughProjectsAlready(User $user): bool
    {
        return count($user->isAssignedTo()) > 3;
    }
}
