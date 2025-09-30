<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount;

interface DiscountCalculator
{
    public function finalPrice(
        float $subtotal,
        string $customerType, // 'REG' | 'VIP'
        string $dateIso,      // 'YYYY-MM-DD'
        ?string $coupon,      // np. 'BLACK' lub null
        bool $firstOrder
    ): float;
}