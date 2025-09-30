<?php

declare(strict_types=1);

namespace TDD\Tests\Helpers\Persistence;

use InvalidArgumentException;
use TDD\Payments\Command\TransactionStatusUpdateCommand;
use TDD\Payments\Persistence\PaymentRepositoryInterface;

final class InMemoryPaymentRepository implements PaymentRepositoryInterface
{
    private array $inMemoryData = [
        'transactions' => [],
        'orders' => [],
    ];

    public function __construct(
        array $transactions = [],
        array $orders = [],
    ) {
        $this->inMemoryData['transactions'] = $transactions;
        $this->inMemoryData['orders'] = $orders;
    }

    public function createPayment(int $transactionId, string $status): void
    {
        $this->inMemoryData['transactions'][$transactionId] = [
            'status' => $status,
        ];
    }

    public function getTransactionStatus(int $transactionId): string
    {
        return $this->inMemoryData['transactions'][$transactionId]['status']
            ?? throw new InvalidArgumentException("Transaction (Id: $transactionId) does not exist.");
    }

    public function getOrderStatus(int $orderId): string
    {
        return $this->inMemoryData['orders'][$orderId]['status']
            ?? throw new InvalidArgumentException("Order (Id: $orderId) does not exist.");
    }

    public function setTransactionStatus(TransactionStatusUpdateCommand $cmd): void
    {
        if (array_key_exists($cmd->getTransactionId(), $this->inMemoryData['transactions'])) {
            throw new InvalidArgumentException("Transaction (Id: {$cmd->getTransactionId()}) does not exist.");
        }

        $this->inMemoryData['transactions'][$cmd->getTransactionId()]['status'] = $cmd->getStatus();
    }
}
