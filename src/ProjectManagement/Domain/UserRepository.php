<?php

declare(strict_types=1);

namespace Freyr\TDD\ProjectManagement\Domain;

interface UserRepository
{
    public function findByEmail(string $email): ?User;
}
