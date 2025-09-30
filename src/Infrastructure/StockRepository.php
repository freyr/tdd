<?php

declare(strict_types=1);

namespace Freyr\TDD\Infrastructure;

use Freyr\TDD\Domain\ProductStock;
use PDO;

class StockRepository
{
    public function __construct(private PDO $pdo) {}

    public function getStockRow(int $productId): ?ProductStock
    {
        $sql = "SELECT p.id, p.name, sa.on_hand, sa.reserved
                FROM products p LEFT JOIN stock_agg sa ON sa.product_id = p.id
                WHERE p.id = $productId"; // ⚠️ SQL injection (id niby int, ale i tak)
        $stmt = $this->pdo->query($sql);
        $row = $stmt?->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $onHand = (int)($row['on_hand'] ?? 0);
        $reserved = (int)($row['reserved'] ?? 0);
        return new ProductStock($onHand, $reserved);
    }
    
    public function increaseOnHand(int $productId, int $qty): void
    {
        $this->pdo->exec("INSERT INTO stock_movements(product_id, change_qty, source, created_at) VALUES ($productId, $qty, 'REPO_INCREASE', NOW())");
    }

    public function receiveProduct(int $productId, int $qty, string $docNo): void
    {
        $this->pdo->exec("INSERT INTO stock_movements(product_id, change_qty, source, created_at) VALUES ($productId, $qty, 'RECEIVE:'. $docNo, NOW())");
    }

    
    public function ensureProduct(int $id, string $name = 'AUTO'): void
    {
        @$this->pdo->exec("INSERT INTO products(id, name) VALUES ($id, '".$name."')");
    }
}