<?php

declare(strict_types=1);

namespace Freyr\TDD\Discount\Policies;

use Freyr\TDD\Discount\CalculatorContext;
use Freyr\TDD\Discount\PricingPolicyInterface;

final class RegularCustomerPricingPolicy implements PricingPolicyInterface
{
    public function calculate(float $price, CalculatorContext $context): float
    {
        if (
            $context->isCustomerRegular()
            && $context->isFirstOrder()
            && $price >= 100
        ) {
            $price *= 0.95;
        }

        return $price;
    }
}
