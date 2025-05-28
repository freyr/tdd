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
        // Domain logic placeholder
        return true;
    }
}
