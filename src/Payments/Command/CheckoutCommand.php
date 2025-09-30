<?php

declare(strict_types=1);

namespace TDD\Payments\Command;

final readonly class CheckoutCommand
{
    public function __construct(
        private int $orderId,
        private string $method,
        private int $amount
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
