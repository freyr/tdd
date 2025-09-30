<?php

declare(strict_types=1);

namespace Freyr\TDD\Domain;

final class ProductStock
{
    public function __construct(private int $onHand, private int $reserved)
    {
        if ($this->onHand < 0 || $this->reserved < 0) {
            throw new \InvalidArgumentException('Stock values must be non-negative');
        }
    }

    public static function empty(): self
    {
        return new self(0, 0);
    }

    public function onHand(): int
    {
        return $this->onHand;
    }

    public function reserved(): int
    {
        return $this->reserved;
    }

    public function available(): int
    {
        return $this->onHand - $this->reserved;
    }

    public function withReceipt(int $qty): self
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('qty must be positive');
        }
        return new self($this->onHand + $qty, $this->reserved);
    }
}
