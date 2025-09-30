<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use DateTime;
use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class NoneCouponPolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if ($context->isNoneCoupon()) {
            if ($price >= 2000) {
                $price *= 0.9;
            }
        }

        return $price;
    }
}
