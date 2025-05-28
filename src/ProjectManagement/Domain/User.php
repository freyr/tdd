<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

class User
{
    public function __construct(
        private string $email
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }
}
