<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class Project
{
    public function __construct(
        public readonly ProjectId $id,
    ) {}
}