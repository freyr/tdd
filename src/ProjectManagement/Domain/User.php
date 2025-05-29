<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class User
{
    public function __construct(
        private string $email,
        private bool $isActive,
        private array $projects,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return array<Project>
     */
    public function isAssignedTo(): array
    {
        return $this->projects;
    }

}
