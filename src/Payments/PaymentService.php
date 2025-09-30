<?php

declare(strict_types=1);

namespace TDD\Payments;

use TDD\Payments\Command\CheckoutCommand;
use TDD\Payments\Command\Factory\ChargeCommandFactory;
use TDD\Payments\Command\Factory\TransactionStatusUpdateCommandFactory;
use TDD\Payments\DTO\ReceiptDTO;
use TDD\Payments\Persistence\PaymentRepositoryInterface;
use Throwable;

final readonly class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
        private PaymentRepositoryInterface $repository
    ) {
    }

    public function checkout(CheckoutCommand $cmd): ?ReceiptDTO
    {
        $orderStatus = $this->repository->getOrderStatus($cmd->getOrderId());
        if ($orderStatus !== 'pending') {
            return null;
        }

        $transactionId = $this->paymentGateway->preparePayment();

        try {
            $receipt = $this->paymentGateway->charge(
                ChargeCommandFactory::createFromCheckoutCommandAndTransactionId($cmd, $transactionId)
            );

            $this->repository->setTransactionStatus(
                TransactionStatusUpdateCommandFactory::createDoneFromTransactionId($transactionId)
            );
        } catch (Throwable) {
            $this->repository->setTransactionStatus(
                TransactionStatusUpdateCommandFactory::createFailedFromTransactionId($transactionId)
            );

            return null;
        }

        return $receipt;
    }
}
