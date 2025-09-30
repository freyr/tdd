<?php

declare(strict_types=1);

namespace TDD\Payments\Command;

final readonly class TransactionStatusUpdateCommand
{
    public function __construct(
        private int $transactionId,
        private string $status
    ) {
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
