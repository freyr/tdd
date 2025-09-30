<?php

declare(strict_types=1);

namespace TDD\Payments\Command\Factory;

use TDD\Payments\Command\TransactionStatusUpdateCommand;

final readonly class TransactionStatusUpdateCommandFactory
{
    public static function createDoneFromTransactionId(int $transactionId): TransactionStatusUpdateCommand
    {
        return new TransactionStatusUpdateCommand($transactionId, 'done');
    }

    public static function createFailedFromTransactionId(int $transactionId): TransactionStatusUpdateCommand
    {
        return new TransactionStatusUpdateCommand($transactionId, 'failed');
    }
}
