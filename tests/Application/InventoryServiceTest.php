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
        $fakes = $this->makeFakes();
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $result = $svc->receiveProduct(10, 0, 'PZ/1');

        $this->assertSame(['error' => 'Qty must be positive'], $result);
        $this->assertCount(0, $fakes['pdo']->execLog); // no DB side-effects
        $this->assertCount(0, $fakes['mailer']->sent);
    }

    public function testReceiveProductUpdatesProjectionAndDoesNotEmailWhenAvailableIsAtMost1000(): void
    {
        $fakes = $this->makeFakes();
        $fakes['repo']->row = ['on_hand' => 900, 'reserved' => 100]; // available before = 800
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $res = $svc->receiveProduct(5, 200, 'PZ/2025/09'); // on_hand becomes 1100, available 1000

        // Return payload
        $this->assertSame(5, $res['product_id']);
        $this->assertSame(200, $res['received']);
        $this->assertSame(1000, $res['available']);

        // SQL executed
        $this->assertNotEmpty($fakes['pdo']->execLog);
        $this->assertStringContainsString("INSERT INTO stock_movements", $fakes['pdo']->execLog[0]);
        $this->assertStringContainsString("RECEIVE:PZ/2025/09", $fakes['pdo']->execLog[0]);

        // Projection updated
        $stored = $fakes['projection']->store[5] ?? null;
        $this->assertIsArray($stored);
        $this->assertSame(1100, $stored['on_hand']);
        $this->assertSame(100, $stored['reserved']);
        $this->assertSame(1000, $stored['available']);
        $this->assertArrayHasKey('updated_at', $stored);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $stored['updated_at']); // ISO-ish

        // No email when available <= 1000
        $this->assertCount(0, $fakes['mailer']->sent);
    }

    public function testReceiveProductSendsEmailWhenAvailableExceeds1000(): void
    {
        $fakes = $this->makeFakes();
        $fakes['repo']->row = ['on_hand' => 1000, 'reserved' => 0];
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $svc->receiveProduct(7, 1, 'PZ/2'); // available -> 1001

        $this->assertCount(1, $fakes['mailer']->sent);
        [$to, $subject, $body] = $fakes['mailer']->sent[0];
        $this->assertSame('supply@company.local', $to);
        $this->assertStringContainsString('product 7', $subject);
        $this->assertStringContainsString('Available: 1001', $body);
    }

    public function testReserveProductThrowsWhenQtyLessThanOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('qty');

        $fakes = $this->makeFakes();
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);
        $svc->reserveProduct(5, 0, 'cust-1');
    }

    public function testReserveProductCreatesReservationWhenEnoughAvailable(): void
    {
        $fakes = $this->makeFakes();
        $fakes['repo']->row = ['on_hand' => 10, 'reserved' => 2]; // available = 8
        $fakes['pdo']->lastInsertIdValue = '42';
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $out = $svc->reserveProduct(9, 5, 'C-123');

        // SQL calls
        $this->assertCount(2, $fakes['pdo']->execLog);
        $this->assertStringContainsString("INSERT INTO reservations", $fakes['pdo']->execLog[0]);
        $this->assertStringContainsString("product_id, customer_id, qty, status", $fakes['pdo']->execLog[0]);
        $this->assertStringContainsString("UPDATE stock_agg SET reserved = reserved + 5 WHERE product_id = 9", $fakes['pdo']->execLog[1]);

        // Projection was updated based on previous empty state
        $updated = $fakes['projection']->store[9] ?? [];
        $this->assertSame(5, $updated['reserved']);
        $this->assertSame(-5, $updated['available']); // current behavior uses proj on_hand (0) minus reserved

        // Return payload
        $this->assertSame('reserved', $out['status']);
        $this->assertSame(9, $out['product_id']);
        $this->assertSame(5, $out['qty']);
        $this->assertSame('C-123', $out['customer_id']);
        $this->assertSame('42', $out['reservation_id']);
    }

    public function testReserveProductReturnsErrorWhenInsufficientAndCacheNotEnough(): void
    {
        $fakes = $this->makeFakes();
        $fakes['repo']->row = ['on_hand' => 3, 'reserved' => 3]; // available = 0
        $fakes['redis']->hashes['stock:11'] = ['available' => 2]; // cache not enough for qty=5
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $out = $svc->reserveProduct(11, 5, 'C-555');

        $this->assertSame(['error' => 'Not enough stock', 'available' => 0], $out);
        $this->assertCount(0, $fakes['pdo']->execLog); // no DB writes
    }

    public function testReserveProductFallsBackToCacheWhenInsufficientButCacheHasEnough(): void
    {
        $fakes = $this->makeFakes();
        $fakes['repo']->row = ['on_hand' => 3, 'reserved' => 3]; // available = 0
        $fakes['redis']->hashes['stock:12'] = ['available' => 10, 'on_hand' => 10, 'reserved' => 0];
        $fakes['pdo']->lastInsertIdValue = '777';
        $svc = new InventoryService($fakes['repo'], $fakes['projection'], $fakes['mailer'], $fakes['pdo'], $fakes['redis'], $fakes['clock']);

        $out = $svc->reserveProduct(12, 4, 'ACME');

        // Should proceed with reservation (no early error)
        $this->assertSame('reserved', $out['status']);
        $this->assertSame('777', $out['reservation_id']);

        // Two SQL statements executed
        $this->assertCount(2, $fakes['pdo']->execLog);

        // Projection updated based on previous projection state (which is empty by default)
        $updated = $fakes['projection']->store[12] ?? [];
        $this->assertSame(4, $updated['reserved']);
        $this->assertSame(-4, $updated['available']);
    }

    /**
     * Helpers: make fakes for dependencies capturing current behavior contracts.
     * These fakes implement minimal surface used by InventoryService.
     */
    private function makeFakes(): array
    {
        $pdo = new class extends PDO {
            public array $execLog = [];
            public string $lastInsertIdValue = '';
            public function __construct() { /* no parent */ }
            public function exec(string $statement): int|false { $this->execLog[] = $statement; return 1; }
            public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): \PDOStatement|false { return false; }
            public function lastInsertId(?string $name = null): string|false { return $this->lastInsertIdValue ?: '0'; }
        };

        $redis = new class extends Redis {
            public array $hashes = [];
            public array $expires = [];
            public function __construct() { /* no parent */ }
            public function hMset($key, $hash): bool { $this->hashes[$key] = array_merge($this->hashes[$key] ?? [], $hash); return true; }
            public function hGetAll($key): array { return $this->hashes[$key] ?? []; }
            public function expire(string $key, int $timeout, ?string $mode = null): bool { $this->expires[$key] = $timeout; return true; }
        };

        $projection = new class($redis) extends StockProjection {
            public array $store = [];
            public function __construct(private Redis $fakeRedis) {}
            public function set(int $productId, array $payload): void { $this->store[$productId] = $payload; /* deterministic, no TTL */ }
            public function get(int $productId): array { return $this->store[$productId] ?? []; }
        };

        $repo = new class(new class extends PDO { public function __construct(){} }) extends StockRepository {
            public ?array $row = null;
            public function __construct(private PDO $fakePdo) { /* don't call parent */ }
            public function getStockRow(int $productId): ?array { return $this->row; }
        };

        $mailer = new class extends EmailNotifier {
            public array $sent = [];
            public function send(string $to, string $subject, string $body): void { $this->sent[] = [$to, $subject, $body]; }
        };

        $clock = new Clock();

        return [
            'pdo' => $pdo,
            'redis' => $redis,
            'projection' => $projection,
            'repo' => $repo,
            'mailer' => $mailer,
            'clock' => $clock,
        ];
    }
}
