<?php

declare(strict_types=1);

namespace TDD\Payments\Persistence;

use TDD\Payments\Command\TransactionStatusUpdateCommand;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function createPayment(int $transactionId, string $status): void
    {
        // TODO: Implement createPayment() method.
    }

    public function getTransactionStatus(int $transactionId): string
    {
        // TODO: Implement getTransactionStatus() method.
    }

    public function getOrderStatus(int $orderId): string
    {
        // TODO: Implement getOrderStatus() method.
    }

    public function setTransactionStatus(TransactionStatusUpdateCommand $cmd): void
    {
        // TODO: Implement setTransactionStatus() method.
    }
}
