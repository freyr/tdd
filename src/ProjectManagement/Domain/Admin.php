<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class Admin
{
    private array $preferences;

    public function __construct(array $preferences)
    {
        $this->preferences = $preferences;
    }

    public function canAssignToProjects(): bool
    {
        return ($this->preferences['canAssignToProjects'] ?? false) === true;
    }
}
