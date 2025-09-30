<?php

declare(strict_types=1);

namespace TDD\Tests\Payments;

use RuntimeException;
use TDD\Payments\Command\CheckoutCommand;
use TDD\Payments\PaymentGatewayInterface;
use TDD\Payments\PaymentService;
use TDD\Payments\PaymentServiceInterface;
use TDD\Payments\Persistence\PaymentRepository;
use TDD\Tests\AbstractTestCase;
use TDD\Tests\Helpers\ClassGenerators\ReceiptDTOClassGenerator;
use TDD\Tests\Helpers\Persistence\InMemoryPaymentRepository;

final class PaymentServiceTest extends AbstractTestCase
{
    private function buildService(array $constructorArgs = []): PaymentServiceInterface
    {
        return new PaymentService(
            $constructorArgs['paymentGateway'] ?? $this->getMockBuilder(PaymentGatewayInterface::class)
                ->getMock(),
            $constructorArgs['repository'] ?? $this->getMockBuilder(PaymentRepository::class)
                ->getMock()
        );
    }

    public function testPendingOrderProcessing(): void
    {
        // Given
        $orderId = 12084;
        $orderStatus = 'pending';
        $paymentMethod = 'paypall';
        $paymentAmount = 500;
        $paymentStatus = 'done';
        $transactionId = 177;

        $receipt = ReceiptDTOClassGenerator::any([
            'status' => $paymentStatus,
            'amount' => $paymentAmount,
            'transactionId' => $transactionId,
        ]);

        $orders = [
            $orderId => [
                'status' => $orderStatus,
            ],
        ];

        // When
        $paymentGateway = $this->getMockBuilder(PaymentGatewayInterface::class)->getMock();
        $paymentGateway->expects($this->once())
            ->method('preparePayment')
            ->willReturn($transactionId);
        $paymentGateway->expects($this->once())
            ->method('charge')
            ->willReturn($receipt);

        $repository = new InMemoryPaymentRepository([], $orders);

        $service = $this->buildService([
            'paymentGateway' => $paymentGateway,
            'repository' => $repository,
        ]);

        // Then
        $this->assertEquals(
            $receipt,
            $service->checkout(new CheckoutCommand(
                $orderId,
                $paymentMethod,
                $paymentAmount
            ))
        );

        $this->assertSame(
            $paymentStatus,
            $repository->getTransactionStatus($transactionId)
        );
    }

    public function testDoneOrderIsNotProcessing(): void
    {
        // Given
        $orderId = 1702;
        $orderStatus = 'done';

        $paymentMethod = 'paypall';
        $paymentAmount = 500;

        $orders = [
            $orderId => [
                'status' => $orderStatus,
            ],
        ];

        // When
        $paymentGateway = $this->getMockBuilder(PaymentGatewayInterface::class)->getMock();
        $paymentGateway->expects($this->never())
            ->method('preparePayment');
        $paymentGateway->expects($this->never())
            ->method('charge');

        $repository = new InMemoryPaymentRepository([], $orders);

        $service = $this->buildService([
            'paymentGateway' => $paymentGateway,
            'repository' => $repository,
        ]);

        // Then
        $receipt = $service->checkout(new CheckoutCommand(
            $orderId,
            $paymentMethod,
            $paymentAmount
        ));

        $this->assertNull($receipt);
    }

    public function testTransactionFailsUponChargeException(): void
    {
        // Given
        $orderId = 12085;
        $orderStatus = 'pending';
        $paymentMethod = 'paypall';
        $paymentAmount = 500;
        $paymentStatus = 'failed';
        $transactionId = 177;

        $orders = [
            $orderId => [
                'status' => $orderStatus,
            ],
        ];

        // When
        $paymentGateway = $this->getMockBuilder(PaymentGatewayInterface::class)->getMock();
        $paymentGateway->expects($this->once())
            ->method('preparePayment')
            ->willReturn($transactionId);
        $paymentGateway->expects($this->once())
            ->method('charge')
            ->willThrowException(new RuntimeException());

        $repository = new InMemoryPaymentRepository([], $orders);

        $service = $this->buildService([
            'paymentGateway' => $paymentGateway,
            'repository' => $repository,
        ]);

        // Then
        $receipt = $service->checkout(new CheckoutCommand(
            $orderId,
            $paymentMethod,
            $paymentAmount
        ));

        $this->assertNull($receipt);
        $this->assertSame(
            $paymentStatus,
            $repository->getTransactionStatus($transactionId)
        );
    }
}
