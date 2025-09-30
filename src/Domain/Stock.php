<?php

declare(strict_types=1);

namespace Freyr\TDD\Domain;

class Stock
{
    public int $onHand = 0;
    public int $reserved = 0;

    public function __construct(int $onHand = 0, int $reserved = 0)
    {
        $this->onHand = $onHand;
        $this->reserved = $reserved;
    }

    public function available(): int
    {
        return $this->onHand - $this->reserved;
    }

    public function reserve(int $qty): void
    {
        if ($qty <= 0) return;
        if ($this->available() < $qty) {
            throw new \RuntimeException('Not enough');
        }
        $this->reserved += $qty;
    }
}