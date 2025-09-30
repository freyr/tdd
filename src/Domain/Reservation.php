<?php

declare(strict_types=1);

namespace Freyr\TDD\Domain;

class Reservation
{
    public function __construct(
        public int $id,
        public int $productId,
        public string $customerId,
        public int $qty,
        public string $status = 'OPEN',
        public string $createdAt = ''
    ) {}
}