<?php

declare(strict_types=1);

namespace Freyr\TDD\Application;

use Freyr\TDD\Domain\ProductStock;
use Freyr\TDD\Infrastructure\EmailNotifier;
use Freyr\TDD\Infrastructure\StockProjection;
use Freyr\TDD\Infrastructure\StockRepository;
use Freyr\TDD\Shared\Clock;
use InvalidArgumentException;
use PDO;
use Redis;

class InventoryService
{
    public function __construct(
        private StockRepository $repo,
        private StockProjection $projection,
        private EmailNotifier $mailer,
        private PDO $pdo,
        private Redis $redis,
        private Clock $clock
    ) {}

    /**
     * Przyjęcie towaru na magazyn (PRZYJĘCIE ZEWNĘTRZNE)
     */
    public function receiveProduct(int $productId, int $qty, string $docNo): ProductStock
    {
        if ($qty <= 0) {
            throw new InvalidArgumentException('QTY must be positive');
        }

        $this->repo->receiveProduct($productId, $qty, $docNo);

        $stock = $this->repo->getStockRow($productId)->withReceipt($qty);


        $this->projection->set($productId, [
            'on_hand' => $stock->onHand(),
            'reserved' => $stock->reserved(),
            'available' => $stock->available(),
            'updated_at' => date('c')
        ]);

        if ($stock->available() > 1000) {
            $this->mailer->send(
                'supply@company.local',
                'High stock for product ' . $productId,
                'Available: ' . $stock->available()
            );
        }

        return $stock;
    }

    /**
     * Rezerwacja towaru
     */
    public function reserveProduct(int $productId, int $qty, string $customerId): array
    {
        if ($qty < 1) {
            throw new InvalidArgumentException('qty');
        }

        $stock = $this->repo->getStockRow($productId);
        $available = $stock->available();

        if ($available < $qty) {
            $cached = $this->redis->hGetAll('stock:' . $productId);
            if (!empty($cached) && (($cached['available'] ?? 0) >= $qty)) {
                $available = (int)$cached['available'];
            } else {
                return ['error' => 'Not enough stock', 'available' => $available];
            }
        }

        $this->pdo->exec(
            "INSERT INTO reservations(product_id, customer_id, qty, status, created_at) VALUES ($productId, '" . $customerId . "', $qty, 'OPEN', NOW())"
        );
        $this->pdo->exec(
            "UPDATE stock_agg SET reserved = reserved + $qty WHERE product_id = $productId"
        );



        $proj = $this->projection->get($productId);
        $proj['reserved'] = ($proj['reserved'] ?? 0) + $qty;
        $proj['available'] = ($proj['on_hand'] ?? 0) - $proj['reserved'];
        $this->projection->set($productId, $proj);


        return [
            'status' => 'reserved',
            'product_id' => $productId,
            'qty' => $qty,
            'customer_id' => $customerId,
            'reservation_id' => $this->pdo->lastInsertId() ?: uniqid('rsv_'),
        ];
    }
}