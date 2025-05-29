<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class ProjectManager
{
    public function assignUserToProject(User $user, Admin $admin, ProjectId $projectId): bool
    {
        if (!$admin->canAssignToProjects()) {
            return false;
        }

        if (!$user->isActive()) {
            return false;
        }

        if (count($user->isAssignedTo()) < 3) {

        }
        // Domain logic placeholder
        return true;
    }
}
