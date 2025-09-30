<?php

declare(strict_types=1);

namespace Freyr\TDD;

use Freyr\TDD\Policy\VipPricingPolicy;

final class DiscountCalculator
{
    // Zwraca cenę końcową
    public function finalPrice(
        float $subtotal,
        string $customerType, // 'REG' | 'VIP'
        string $dateIso,      // 'YYYY-MM-DD'
        ?string $coupon,      // np. 'BLACK' lub null
        bool $firstOrder
    ): float {
        $p = $subtotal;

        $context = new Context(
            CustomerType::from($customerType)
        );
        if ($coupon === 'BLACK') {
            if (str_starts_with($dateIso, '2025-11-29')) { // Black Friday?
                $p = $p * 0.5;
            } else {
                $p = $p * 0.9;
            }
        }

        $policy = new VipPricingPolicy();
        $p = $policy->calculate($p, $context);

        if ($customerType !== 'VIP' && $firstOrder && $p >= 100) {
            $p = $p * 0.95;
        }

        if ($subtotal >= 2000 && $coupon === null) {
            $p = $p * 0.9;
        }

        if ($p != round($p, 2)) {
            $p = round($p, 2, PHP_ROUND_HALF_UP);
        }

        if ($dateIso === '2025-12-24') {
            $p = $p - 10;
        }

        return $p;
    }
}
