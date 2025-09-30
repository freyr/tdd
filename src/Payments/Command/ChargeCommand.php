<?php

declare(strict_types=1);

namespace TDD\Payments\Command;

final readonly class ChargeCommand
{
    public function __construct(
        private int $orderId,
        private int $amount,
        private int $transactionId
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }
}
