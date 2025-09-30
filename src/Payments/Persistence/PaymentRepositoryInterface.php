<?php

declare(strict_types=1);

namespace TDD\Payments\Persistence;

use TDD\Payments\Command\TransactionStatusUpdateCommand;

interface PaymentRepositoryInterface
{
    public function createPayment(int $transactionId, string $status): void;

    public function getTransactionStatus(int $transactionId): string;

    public function setTransactionStatus(TransactionStatusUpdateCommand $cmd): void;

    public function getOrderStatus(int $orderId): string;
}
