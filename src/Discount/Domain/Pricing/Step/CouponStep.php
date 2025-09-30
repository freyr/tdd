<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing\Step;

use Freyr\TDD\Discount\Domain\Enum\Coupon;
use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;

final class CouponStep implements PricingStep
{
    public function apply(float $price, Context $context): float
    {
        if ($context->coupon === Coupon::BLACK) {
            if ($this->isBlackFridayDay($context)) {
                return $price * 0.5;
            }
            return $price * 0.9;
        }
        return $price;
    }

    private function isBlackFridayDay(Context $context): bool
    {
        return $context->date->format('Y-m-d') === '2025-11-29';
    }
}
