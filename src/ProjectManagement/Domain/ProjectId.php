<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class ProjectId
{
    public function __construct(
        private string $id,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
}
