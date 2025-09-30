<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Application;

use Freyr\TDD\Application\InventoryService;
use Freyr\TDD\Infrastructure\EmailNotifier;
use Freyr\TDD\Infrastructure\StockProjection;
use Freyr\TDD\Infrastructure\StockRepository;
use Freyr\TDD\Shared\Clock;
use PDO;
use PHPUnit\Framework\TestCase;
use Redis;

final class InventoryServiceTest extends TestCase
{
    public function testReceiveProductReturnsErrorOnNonPositiveQty(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $projection = $this->createMock(StockProjection::class);
        $mailer = $this->createMock(EmailNotifier::class);
        $pdo = $this->createMock(PDO::class);
        $redis = $this->createStub(Redis::class);

        $pdo->expects($this->never())->method('exec');
        $projection->expects($this->never())->method('set');
        $mailer->expects($this->never())->method('send');

        $svc = new InventoryService($repo, $projection, $mailer, $pdo, $redis, new Clock());

        $result = $svc->receiveProduct(10, 0, 'PZ/1');

        $this->assertSame(['error' => 'Qty must be positive'], $result);
    }

    public function testReceiveProductUpdatesProjectionAndDoesNotEmailWhenAvailableIsAtMost1000(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $repo->method('getStockRow')->willReturn(['on_hand' => 900, 'reserved' => 100]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with($this->stringContains("RECEIVE:PZ/2025/09"))
            ->willReturn(1);

        $projection = $this->createMock(StockProjection::class);
        $projection->expects($this->once())
            ->method('set')
            ->with(
                5,
                $this->callback(function(array $payload) {
                    $this->assertSame(1100, $payload['on_hand']);
                    $this->assertSame(100, $payload['reserved']);
                    $this->assertSame(1000, $payload['available']);
                    $this->assertArrayHasKey('updated_at', $payload);
                    $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $payload['updated_at']);
                    return true;
                })
            );

        $mailer = $this->createMock(EmailNotifier::class);
        $mailer->expects($this->never())->method('send');

        $svc = new InventoryService($repo, $projection, $mailer, $pdo, $this->createStub(Redis::class), new Clock());

        $res = $svc->receiveProduct(5, 200, 'PZ/2025/09');

        $this->assertSame(5, $res['product_id']);
        $this->assertSame(200, $res['received']);
        $this->assertSame(1000, $res['available']);
    }

    public function testReceiveProductSendsEmailWhenAvailableExceeds1000(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $repo->method('getStockRow')->willReturn(['on_hand' => 1000, 'reserved' => 0]);

        $projection = $this->createMock(StockProjection::class);
        $projection->expects($this->once())
            ->method('set')
            ->with(
                7,
                $this->callback(function(array $payload) {
                    $this->assertSame(1001, $payload['available']);
                    return true;
                })
            );

        $mailer = $this->createMock(EmailNotifier::class);
        $mailer->expects($this->once())
            ->method('send')
            ->with(
                'supply@company.local',
                $this->stringContains('product 7'),
                $this->stringContains('Available: 1001')
            );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('exec')->willReturn(1);

        $svc = new InventoryService($repo, $projection, $mailer, $pdo, $this->createStub(Redis::class), new Clock());

        $svc->receiveProduct(7, 1, 'PZ/2');
    }

    public function testReserveProductThrowsWhenQtyLessThanOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('qty');

        $svc = new InventoryService(
            $this->createStub(StockRepository::class),
            $this->createStub(StockProjection::class),
            $this->createStub(EmailNotifier::class),
            $this->createStub(PDO::class),
            $this->createStub(Redis::class),
            new Clock()
        );

        $svc->reserveProduct(5, 0, 'cust-1');
    }

    public function testReserveProductCreatesReservationWhenEnoughAvailable(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $repo->method('getStockRow')->willReturn(['on_hand' => 10, 'reserved' => 2]);

        $projection = $this->createMock(StockProjection::class);
        $projection->method('get')->willReturn([]);
        $projection->expects($this->once())
            ->method('set')
            ->with(
                9,
                $this->callback(function(array $payload) {
                    $this->assertSame(5, $payload['reserved']);
                    $this->assertSame(-5, $payload['available']);
                    return true;
                })
            );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('lastInsertId')->willReturn('42');
        $execLog = [];
        $pdo->expects($this->exactly(2))
            ->method('exec')
            ->willReturnCallback(function(string $sql) use (&$execLog) {
                $execLog[] = $sql;
                return 1;
            });

        $svc = new InventoryService($repo, $projection, $this->createStub(EmailNotifier::class), $pdo, $this->createStub(Redis::class), new Clock());

        $out = $svc->reserveProduct(9, 5, 'C-123');

        $this->assertCount(2, $execLog);
        $this->assertStringContainsString('INSERT INTO reservations', $execLog[0]);
        $this->assertStringContainsString('product_id, customer_id, qty, status', $execLog[0]);
        $this->assertStringContainsString('UPDATE stock_agg SET reserved = reserved + 5 WHERE product_id = 9', $execLog[1]);

        $this->assertSame('reserved', $out['status']);
        $this->assertSame(9, $out['product_id']);
        $this->assertSame(5, $out['qty']);
        $this->assertSame('C-123', $out['customer_id']);
        $this->assertSame('42', $out['reservation_id']);
    }

    public function testReserveProductReturnsErrorWhenInsufficientAndCacheNotEnough(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $repo->method('getStockRow')->willReturn(['on_hand' => 3, 'reserved' => 3]);

        $redis = $this->createMock(Redis::class);
        $redis->expects($this->once())
            ->method('hGetAll')
            ->with('stock:11')
            ->willReturn(['available' => 2]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->never())->method('exec');

        $svc = new InventoryService($repo, $this->createStub(StockProjection::class), $this->createStub(EmailNotifier::class), $pdo, $redis, new Clock());

        $out = $svc->reserveProduct(11, 5, 'C-555');

        $this->assertSame(['error' => 'Not enough stock', 'available' => 0], $out);
    }

    public function testReserveProductFallsBackToCacheWhenInsufficientButCacheHasEnough(): void
    {
        $repo = $this->createStub(StockRepository::class);
        $repo->method('getStockRow')->willReturn(['on_hand' => 3, 'reserved' => 3]);

        $redis = $this->createMock(Redis::class);
        $redis->expects($this->once())
            ->method('hGetAll')
            ->with('stock:12')
            ->willReturn(['available' => 10, 'on_hand' => 10, 'reserved' => 0]);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('lastInsertId')->willReturn('777');
        $execLog = [];
        $pdo->expects($this->exactly(2))
            ->method('exec')
            ->willReturnCallback(function(string $sql) use (&$execLog) {
                $execLog[] = $sql;
                return 1;
            });

        $projection = $this->createMock(StockProjection::class);
        $projection->method('get')->willReturn([]);
        $projection->expects($this->once())
            ->method('set')
            ->with(
                12,
                $this->callback(function(array $payload) {
                    $this->assertSame(4, $payload['reserved']);
                    $this->assertSame(-4, $payload['available']);
                    return true;
                })
            );

        $svc = new InventoryService($repo, $projection, $this->createStub(EmailNotifier::class), $pdo, $redis, new Clock());

        $out = $svc->reserveProduct(12, 4, 'ACME');

        $this->assertSame('reserved', $out['status']);
        $this->assertSame('777', $out['reservation_id']);
        $this->assertCount(2, $execLog);
    }
}
