<?php

declare(strict_types=1);

namespace Freyr\TDD\Infrastructure;

use Redis;

class StockProjection
{
    public function __construct(private Redis $redis) {}

    public function set(int $productId, array $payload): void
    {
        $key = 'stock:' . $productId;
        $this->redis->hMset($key, $payload);
        if (rand(0, 10) > 7) { // losowy TTL, super 🙈
            $this->redis->expire($key, 60);
        }
    }

    public function get(int $productId): array
    {
        return $this->redis->hGetAll('stock:' . $productId) ?: [];
    }
}