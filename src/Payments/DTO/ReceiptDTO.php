<?php

declare(strict_types=1);

namespace TDD\Payments\DTO;

final readonly class ReceiptDTO
{
    public function __construct(
        private string $status,
        private int $amount,
        private int $transactionId,
        private string $maskedCard
    ) {
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getMaskedCard(): string
    {
        return $this->maskedCard;
    }
}
