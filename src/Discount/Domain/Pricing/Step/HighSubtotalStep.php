<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Domain\Pricing\Step;

use Freyr\TDD\Discount\Domain\Enum\Coupon;
use Freyr\TDD\Discount\Domain\Pricing\Context;
use Freyr\TDD\Discount\Domain\Pricing\PricingStep;

final class HighSubtotalStep implements PricingStep
{
    public function apply(float $price, Context $context): float
    {
        // Extra 10% only when original subtotal >= 2000 and NO coupon is present (NONE).
        if ($context->subtotal >= 2000 && $context->coupon === Coupon::NONE) {
            return $price * 0.9;
        }
        return $price;
    }
}
